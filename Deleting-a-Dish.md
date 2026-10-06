# Deleting a Dish

This page documents the dish-deletion feature implemented in the Food Festive PHP application.

## Feature overview

| Feature | Route/file | Purpose |
| --- | --- | --- |
| Delete action | `/dishes` | Displays the Delete link for each dish. |
| Delete confirmation | `src/assets/js/dish.js` | Asks the user to confirm deletion. |
| Delete endpoint | `/delete?id={dish_id}` | Receives the delete request and returns JSON. |
| Delete model | `src/models/dish.php` | Finds and deletes the selected dish. |

## Prerequisites

Before deleting a dish:

- The user should be logged in.
- The `users` and `dishes` tables must exist.
- The selected dish must have a valid database ID.
- The dish list must be available at `/dishes`.

The dish list page is protected by the login guard:

```php
include "session.php";
include "require_login.php";
include "models/dish.php";
```

## 1. Display the Delete action

Each dish row in `src/dishes.php` contains a Delete link with the dish ID stored in a `data-id` attribute:

```php
<a href="#" class="btn-delete" data-id="<?= $row['id'] ?>">Delete</a>
```

The `data-id` value tells the JavaScript code which dish should be deleted.

## 2. Confirm the deletion

The JavaScript in `src/assets/js/dish.js` listens for clicks on elements with the `btn-delete` class:

```javascript
const btnDelete = document.getElementsByClassName("btn-delete");

for (let i = 0; i < btnDelete.length; i++) {
    btnDelete[i].addEventListener("click", function (e) {
        e.preventDefault();

        var result = confirm("Do you want to delete this dish?");

        if (result) {
            // Send the delete request after confirmation.
        }
    });
}
```

If the user selects **Cancel**, no request is sent and the dish remains in the list.

## 3. Send the delete request

After confirmation, the JavaScript reads the dish ID and sends a DELETE request to `/delete`:

```javascript
var btn = this;
var id = this.getAttribute("data-id");

fetch(`/delete?id=${id}`, {
    method: "DELETE",
    headers: {
        "Content-Type": "application/json"
    }
})
    .then(function (response) {
        return response.json();
    })
    .then(function (data) {
        if (data.deleted) {
            btn.closest("tr").remove();
        }
    })
    .catch(function (error) {
        console.log(error);
    });
```

The request uses the dish ID as a query parameter:

```text
/delete?id=12
```

## 4. Process the request in delete.php

The endpoint in `src/delete.php` starts the session, loads the dish model, and checks whether an ID was provided:

```php
<?php
include "session.php";
include "models/dish.php";
include "require_login.php";

$result = [];

if (array_key_exists("id", $_GET)) {
    $result['deleted'] = false;

    $is_deleted = delete_dish($_SESSION['id'], $_GET['id']);

    if ($is_deleted) {
        $result['deleted'] = true;
    }
}

echo json_encode($result);
?>
```

The endpoint returns JSON so the browser can determine whether the row should be removed:

```json
{
    "deleted": true
}
```

If no ID is provided, the endpoint returns an empty JSON object:

```json
{}
```

## 5. Find and delete the dish

The model function in `src/models/dish.php` first looks up the dish by ID:

```php
function delete_dish($current_user, $id) {
    global $conn;
    $flag = false;

    $query = "SELECT * FROM dishes
              WHERE id = '" . $conn->real_escape_string($id) . "'";

    $result = $conn->query($query);
```

If the dish exists, the current implementation deletes the record and returns a success flag:

```php
if ($result->num_rows > 0) {
    $dish = $result->fetch_array(MYSQLI_ASSOC);

    $query = "DELETE FROM dishes
              WHERE id = '" . $dish['id'] . "'";

    if ($conn->query($query)) {
        $flag = true;
    }
}

return $flag;
```

## 6. Remove the row from the page

When the endpoint returns `deleted: true`, JavaScript removes the closest table row without reloading the page:

```javascript
if (data.deleted) {
    btn.closest("tr").remove();
}
```

The deleted dish is therefore removed from the visible list immediately after the database request succeeds.

## Complete deletion flow

1. The user opens `/dishes`.
2. The application displays each dish with a Delete link.
3. The user selects **Delete**.
4. JavaScript displays a confirmation dialog.
5. If confirmed, JavaScript sends `DELETE /delete?id={dish_id}`.
6. `delete.php` calls `delete_dish()`.
7. The model finds the dish and attempts to delete it.
8. The endpoint returns JSON.
9. If `deleted` is true, JavaScript removes the dish row from the page.

## Test checklist

- [ ] A logged-in user can see the Delete link.
- [ ] Selecting Delete displays a confirmation dialog.
- [ ] Selecting Cancel keeps the dish.
- [ ] Confirming deletion sends a request containing the correct dish ID.
- [ ] A successful deletion returns `{"deleted":true}`.
- [ ] The deleted dish row disappears from the list.
- [ ] Deleting a non-existent dish does not report success.
- [ ] Requests without a valid session are rejected.
- [ ] Confirm the intended ownership behavior; the current query deletes by dish ID without checking `user_id`.

## Implementation notes

### Current deletion behavior

The ownership comparison was removed from the delete condition. The current code now marks the deletion as successful when the database DELETE query succeeds:

```php
if ($conn->query($query)) {
    $flag = true;
}
```

The `delete_dish()` function still receives `$current_user`, and `delete.php` still passes `$_SESSION['id']`, but `$current_user` is no longer used by the current deletion query.

### Login protection

`src/delete.php` now includes `require_login.php`:

```php
include "session.php";
include "models/dish.php";
include "require_login.php";
```

Unauthenticated requests are redirected to the login page before `$_SESSION['id']` is used.

### Recommended ownership protection

Because the ownership comparison was removed, any authenticated user who knows a dish ID may be able to delete that dish. If dishes should only be deleted by their creator, add the user ID to the DELETE condition:

```php
$delete_query = "DELETE FROM dishes
                 WHERE id = '" . $conn->real_escape_string($id) . "'
                 AND user_id = '" . $conn->real_escape_string($current_user) . "'";
```

### Check the correct row class after deletion

The dish rows use the class `dish-item`:

```php
<tr class="dish-item">
```

But `checkDishItems()` currently searches for `todo-item`:

```javascript
let dishItems = document.getElementsByClassName("todo-item");
```

It should search for `dish-item` if the empty-list behavior is required:

```javascript
let dishItems = document.getElementsByClassName("dish-item");
```

### Use a single ownership-safe DELETE query

The safest database condition is to include both the dish ID and the current user ID:

```sql
DELETE FROM dishes
WHERE id = ? AND user_id = ?;
```

This prevents a user from deleting a dish that belongs to another account.
