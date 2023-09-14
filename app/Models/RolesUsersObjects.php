<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Permission\Models\Role;

/**
 * App\Models\RolesUsersObjects
 *
 * @property int $id
 * @property string $object_class
 * @property int $object_id
 * @property int $user_id
 * @property int $role_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read Role $role
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|RolesUsersObjects newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|RolesUsersObjects newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|RolesUsersObjects query()
 * @method static \Illuminate\Database\Eloquent\Builder|RolesUsersObjects whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RolesUsersObjects whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RolesUsersObjects whereObjectClass($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RolesUsersObjects whereObjectId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RolesUsersObjects whereRoleId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RolesUsersObjects whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RolesUsersObjects whereUserId($value)
 * @mixin \Eloquent
 */
class RolesUsersObjects extends Model
{
  use HasFactory;

  protected $table = 'roles_users_objects';
  protected $fillable = ['user_id', 'role_id', 'object_id', 'object_class'];

  public function user(): BelongsTo
  {
    return $this->belongsTo('App\Models\User');
  }

  public function role(): BelongsTo
  {
    return $this->belongsTo('Spatie\Permission\Models\Role');
  }


  /**
   *
   *
   * @param array $values
   * @param User $user
   * @return void
   */
  public static function setRolesUsersObjects(
    array $values,
    User  $user,
  ): void
  {
    $values_str = [];
    $current_values_str = [];
    $current_values = $user->rolesUsersObjects;
    foreach ($current_values as $current_value) {
      $current_values_str[] = self::convertToString(
        [
          'role_id' => $current_value->role_id,
          'object_id' => $current_value->object_id,
          'object_class' => $current_value->object_class,
          'user_id' => $user->id,
        ]
      );
    }
    foreach ($values as $value) {
      $values_str[] = self::convertToString(
        [
          'role_id' => $value['role_id'],
          'object_id' => $value['object_id'],
          'object_class' => $value['object_class'],
          'user_id' => $user->id,
        ]
      );
    }


    $ar_values_intersect = array_intersect($current_values_str, $values_str);

    //to delete
    $ar_values_delete = array_diff($current_values_str, $ar_values_intersect);
    foreach ($ar_values_delete as $value_str) {
      $value = self::convertToArray($value_str);
      $to_delete_value = self::where([
        'user_id' => $user->id,
        'role_id' => $value['role_id'],
        'object_id' => $value['object_id'],
        'object_class' => $value['object_class'],
      ])->delete();
    }

    //to add
    $ar_values_add = array_diff($values_str, $ar_values_intersect);
    foreach ($ar_values_add as $value_str) {
      $value = self::convertToArray($value_str);
      $to_add_value = self::create([
        'user_id' => $user->id,
        'role_id' => $value['role_id'],
        'object_id' => $value['object_id'],
        'object_class' => $value['object_class'],
      ]);
    }

  }

  /**
   * @param User $user
   * @param string $object_class
   * @param int $object_id
   * @param int $role_id
   * @return bool
   */
  public static function userCan(
    User   $user,
    string $object_class,
    int    $object_id,
    int    $role_id
  ): bool
  {
    $roles = $user->rolesUsersObjects;
    foreach ($roles as $role) {
      if (
        $role->object_class == $object_class &&
        $role->object_id == $object_id &&
        $role->role_id == $role_id
      ) {
        return true;
      }
    }
    return false;
  }

  public static function convertToString($roles_objects): string
  {
    return http_build_query((array)$roles_objects);
  }

  public static function convertToArray($str): array
  {
    $output = [];
    parse_str($str, $output);
    return $output;
  }
}
