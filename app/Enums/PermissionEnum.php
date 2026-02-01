<?php

namespace App\Enums;

/**
 * Permission definitions for Medical AI Content Generator
 */
class PermissionEnum
{
    // User Management
    const USERS = "Users";
    const USERS_ADD = "Users Add";
    const USERS_DELETE = "Users Delete";
    const USERS_VIEW = "Users View";
    const USERS_UPDATE = "Users Update";

    // Settings Management
    const SETTING = "Setting";
    const SETTING_ADD = "Setting Add";
    const SETTING_DELETE = "Setting Delete";
    const SETTING_VIEW = "Setting View";
    const SETTING_UPDATE = "Setting Update";

    // Role Management
    const MANAGE_ROLES = "Manage roles";

    // Subscription Management
    const MANAGE_SUBSCRIPTIONS = "Manage subscriptions";

    // Content Generation
    const CONTENT_GENERATION = "Content generation";
    const CONTENT_GENERATION_CREATE = "Content generation Create";
    const CONTENT_GENERATION_VIEW = "Content generation View";
    const CONTENT_GENERATION_EXPORT = "Content generation Export";

    // Medical Specialties Management
    const MANAGE_SPECIALTIES = "Manage specialties";
    const MANAGE_SPECIALTIES_ADD = "Manage specialties Add";
    const MANAGE_SPECIALTIES_UPDATE = "Manage specialties Update";
    const MANAGE_SPECIALTIES_DELETE = "Manage specialties Delete";
    const MANAGE_SPECIALTIES_VIEW = "Manage specialties View";

    // Prompts Management
    const MANAGE_PROMPTS = "Manage prompts";
    const MANAGE_PROMPTS_ADD = "Manage prompts Add";
    const MANAGE_PROMPTS_UPDATE = "Manage prompts Update";
    const MANAGE_PROMPTS_DELETE = "Manage prompts Delete";
    const MANAGE_PROMPTS_VIEW = "Manage prompts View";
}
