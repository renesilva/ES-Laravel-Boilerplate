<?php

return [
  'id' => ['id'],
  'password' => ['password', 'password_meta'],
  'profile_picture' => ['profile_picture'],
  'personal' => [
    'name',
    'lastname',
    'email',
    'optional_email',
    'phonenumber',
    'birth_date',
    'institution',
    'job_function',
  ],
  'social' => [
    'facebook',
    'linkedin',
    'youtube',
    'twitter',
    'personal_website',
    'skype',
    'instagram',
    'optional_phonenumber',
  ],
  'more' => [
    'user-location',
  ],
  'admin' => [
    'language',
    'slug',
    'username',
    'is_searchable',
    'is_active',
    'is_admin',
    'user_key',
    'two_step_verification_is_active',
    'is_confidential',
  ],
  //doesn't matter if it's a copy of the other fields
  'tax_rel' => [
    'user-location',
  ],
  'dates' => [
    'created_at',
    'updated_at',
    'last_visited',
  ],
  'device_token' => ['device_token'],
];
