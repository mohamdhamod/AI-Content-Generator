# Team Collaboration System Documentation

## Overview

The Team Collaboration system allows users to create teams, invite members, and collaborate on content generation. Each team member uses their **own subscription quota** - teams do not share content generation limits.

## Features

### 1. Team Management
- **Create Teams**: Users can create new team workspaces
- **View Teams**: See all teams you're a member of
- **Team Roles**: Owner, Admin, Editor, Reviewer, Viewer

### 2. Invitation System (Email-based)
The invitation system supports both registered and non-registered users:

#### For Registered Users:
1. Owner/Admin invites by email
2. User receives email with "Accept Invitation" button
3. User clicks link → redirected to login if not logged in
4. After login → automatically joined to team

#### For Non-Registered Users:
1. Owner/Admin invites by email
2. User receives email with "Create Account & Join Team" button
3. User clicks link → redirected to registration page
4. After registration → automatically linked to pending invitation
5. On next login → automatically joined to team

### 3. Content Collaboration
- **Assign Content**: Assign generated content to team members
- **Comments**: Add comments and annotations to content
- **Task Management**: Track assignments with priorities and due dates

## Database Structure

### team_members table additions:
```sql
- invitation_token (varchar 64, unique) - For secure email links
- pending_email (varchar) - For non-registered user invitations
- invitation_expires_at (timestamp) - Invitation expiration
- user_id (nullable) - Null for pending non-registered invitations
```

## API Endpoints

### Teams
| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/{locale}/teams` | List user's teams |
| POST | `/{locale}/teams` | Create new team |
| GET | `/{locale}/teams/{id}` | Get team details |
| POST | `/{locale}/teams/{id}/invite` | Invite member by email |
| DELETE | `/{locale}/teams/{id}/members/{userId}` | Remove member |

### Invitations
| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/{locale}/teams/invitation/accept?token=xxx` | Accept invitation by token |
| GET | `/{locale}/teams/invitations/pending` | Get user's pending invitations |
| POST | `/{locale}/invitations/{id}/accept` | Accept invitation by ID |
| POST | `/{locale}/invitations/{id}/decline` | Decline invitation |
| POST | `/{locale}/teams/{teamId}/members/{memberId}/resend-invitation` | Resend invitation |

### Content Collaboration
| Method | Endpoint | Description |
|--------|----------|-------------|
| POST | `/{locale}/content/{id}/assign` | Assign content to member |
| PUT | `/{locale}/assignments/{id}/status` | Update assignment status |
| GET | `/{locale}/my-tasks` | Get user's assigned tasks |
| GET | `/{locale}/content/{id}/comments` | Get content comments |
| POST | `/{locale}/content/{id}/comments` | Add comment |
| POST | `/{locale}/comments/{id}/reply` | Reply to comment |
| PUT | `/{locale}/comments/{id}/resolve` | Resolve comment |

## Email Templates

### Team Invitation Email
Located at: `resources/views/emails/team-invitation.blade.php`

Features:
- RTL support for Arabic
- Different content for new vs existing users
- Role badge display
- Platform features showcase for new users

## Event Listeners

### ProcessPendingTeamInvitation
Triggers on:
- `Illuminate\Auth\Events\Login` - After user login
- `Illuminate\Auth\Events\Registered` - After new user registration

Actions:
1. Checks session for pending invitation token
2. Automatically accepts invitation if valid
3. Links pending invitations by email for new users

## Role Permissions

| Role | Permissions |
|------|-------------|
| Owner | All permissions (*) |
| Admin | create, edit, delete, publish, invite, manage_templates |
| Editor | create, edit, publish |
| Reviewer | view, comment, approve |
| Viewer | view |

## Subscription Model

**Important**: Teams do NOT share subscription quotas.

Each user has their own `max_content_generations` limit from their personal subscription. When a user generates content within a team workspace, it counts against **their own** quota, not a shared team quota.

This allows:
- Free collaboration without subscription pooling
- Fair usage per individual
- Flexible team composition regardless of subscription levels

## UI Components

### Content Generator Page (show.blade.php)
- **Team Collaboration Modal**: View teams, create teams, see invitations
- **Comments Modal**: Add/view comments on content
- **Assign Modal**: Assign content to team members
- **My Tasks Button**: Navigate to personal tasks page

### My Tasks Page (teams/my-tasks.blade.php)
- Lists all assigned tasks
- Filter by status (pending, in_progress, completed)
- Priority indicators
- Due date tracking
- Quick status updates

## Testing the System

1. **Create a Team**:
   - Go to content generator
   - Click "My Teams" button
   - Click "Create Team" tab
   - Fill in team name and create

2. **Invite a Member**:
   - Click "My Teams" button
   - Click the eye icon on a team
   - Click "Invite Member"
   - Enter email and role
   - User receives invitation email

3. **Accept Invitation**:
   - Click link in email
   - Login if required
   - Automatically joined to team

4. **Collaborate**:
   - Generate content
   - Click "Assign" to assign to team member
   - Click "Comments" to discuss content
   - Member can view in "My Tasks"

## Troubleshooting

### Email not sending
- Check mail configuration in `.env`
- Verify SMTP settings
- Check `storage/logs/laravel.log` for errors

### Invitation expired
- Default expiration: 7 days
- Use "Resend Invitation" to send new token

### User not auto-joined after login
- Check `ProcessPendingTeamInvitation` listener is registered
- Verify session contains `pending_team_invitation`
- Check logs for any errors

## Future Enhancements
- [ ] Team-wide content templates
- [ ] Activity feed in team dashboard
- [ ] Bulk invitation via CSV
- [ ] Team analytics and reports
- [ ] Real-time collaboration notifications
