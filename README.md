# Neighbors Helping Neighbors

A community task-sharing web app where people can post tasks they need help with and offer to help neighbors nearby.

Built with Laravel (PHP) and plain HTML/CSS/JS.

---

## What it does

- **Post tasks** — describe something you need help with, add a location, optional photo and scheduled time
- **Browse the feed** — see open tasks posted by others in a swipe-style card UI
- **Offer to help** — swipe right to offer; a match is created and a private chat opens automatically
- **Chat** — each match gets its own conversation between the task poster and the helper
- **Mark complete** — the task owner can close the task from the chat header once done
- **Admin panel** — admins can view all tasks and users, remove tasks, and block/unblock accounts

---

## Used technologies

| Layer | Technology |
|---|---|
| Backend | Laravel 11, PHP |
| Database | MySQL (via DDEV) |
| Frontend | Blade templates, vanilla CSS, vanilla JS |
| Icons | Tabler Icons |
| Fonts | Lora (headings), DM Sans (body) |
| Dev environment | DDEV |

---

## Database structure

| Table | Purpose |
|---|---|
| `users` | Auth, profile, location, role, blocked status |
| `tasks` | Task posts with location, photo, schedule, status |
| `offers` | A user's offer to help with a task |
| `task_matches` | Created when an offer is accepted; owns the chat |
| `messages` | Chat messages belonging to a task match |

Task status flow: `open` → `matched` → `completed` / `closed`

---

## Project structure

```
app/
├── Http/
│   └── Controllers/        # Route handlers (TaskController, FeedController,
│                           #   SwipeController, ChatController, AdminController)
└── Models/
    ├── User.php             # Auth, profile, role, blocked status
    ├── Task.php             # Task posts with location, photo, schedule
    ├── Offer.php            # A user's offer to help
    ├── TaskMatch.php        # Created on match; owns the chat thread
    └── Message.php          # Individual chat messages

database/
└── migrations/              # Schema definitions for all five tables

routes/
└── web.php                  # All application routes (auth, tasks, feed,
                             #   swipe, chat, admin)

resources/views/
├── layouts/
│   └── app.blade.php        # Main shell (sidebar + <main> wrapper)
├── partials/
│   ├── sidebar.blade.php    # Navigation + dark mode toggle
│   └── conversation_list.blade.php  # Chat sidebar panel
├── auth/
│   ├── login.blade.php
│   └── register.blade.php
├── tasks/
│   ├── create.blade.php     # New task form
│   └── edit.blade.php       # Edit task form
├── feed/
│   └── index.blade.php      # Swipe-style task card feed
├── chat/
│   ├── index.blade.php      # Empty state (no conversation selected)
│   └── show.blade.php       # Active conversation view
├── profile/
│   └── show.blade.php       # Profile info + My tasks + Completed tabs
└── admin/
    └── index.blade.php      # All tasks + Users tabs with block/remove actions

public/
├── css/app.css              # All styles — CSS variables for light/dark mode,
│                            #   layout, sidebar, cards, buttons, forms
└── js/app.js                # Dark mode toggle, poster popup, toast
                             #   notifications, card fade animation
```
