<?php
    include "session.php"; 
    include "require_login.php";
    include "models/dish.php";
    include "lib/pagination.php";
   
    if (isset($_GET['page_no'])) {
        $page_no = $_GET['page_no'];
    } else {
        $page_no = 1;
    }

    $offset = get_offset($page_no); // calculate the offset based on the current page number

    if (isset($_SESSION['id'])) {
        $dish_data = get_all_dishes([], ['offset'=> $offset, 'total_records_per_page' => TOTAL_RECORDS_PER_PAGE]);
        
        $dishes = $dish_data['result'] ?? [];
        $total_records = $dish_data['total'] ?? 0;

        $pagy = pagination($total_records, $page_no); // setup pagination
    }
?>
<?php include "layouts/_header.php"; ?>
    <?php include "layouts/_navigation.php"; ?>
    <main class="account">
        <section id="dish" class="container">
            <?php if(isset($_SESSION['flash_message'])) { ?>
                <?php include "layouts/_messages.php"; ?>
            <?php } ?>
            <div id="account-container">               
                <?php include "layouts/_account-navigation.php" ?>
                <div id="account-preview">
                    <div id="account-preview-heading">
                        <h2><i class="fa-solid fa-bowl-food"></i> Dishes</h2>
                        <a href="/new" class="btn btn-sm btn-rounded">Add New Dish</a>
                    </div>
                    <p class="dish-page-intro">Manage your menu, update prices, and keep your dishes up to date.</p>
                    <div class="dish-table-wrap">
                        <table>
                            <thead>
                                <tr>
                                    <th scope="col">Thumbnail</th>
                                    <th scope="col">Name</th>
                                    <th scope="col">Price</th>
                                    <th scope="col">Last Updated</th>
                                    <th scope="col">Date Created</th>
                                    <th scope="col">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if(!empty($dishes)) { ?>
                                    <?php foreach($dishes as $row) { ?>
                                        <tr class="dish-item">
                                            <td>
                                                <div class="thumbnail-image">
                                                    <img src="data:image/jpeg;base64,<?= base64_encode($row['thumbnail']) ?>" alt="<?= htmlspecialchars($row['name'], ENT_QUOTES, 'UTF-8') ?>" class="featured-image">
                                                </div>
                                            </td>
                                            <td class="dish-name"><?= htmlspecialchars($row['name'], ENT_QUOTES, 'UTF-8') ?></td>
                                            <td class="dish-price">₱<?= number_format($row['price'], 2, '.', ',') ?></td>
                                            <td><?= !empty($row['updated_at']) ? date('M d, Y @ h:i a', strtotime($row['updated_at'])) : '-' ?></td>
                                            <td><?= date('M d, Y @ h:i a', strtotime($row['created_at'])) ?></td>
                                            <td class="action-buttons">
                                                <a href="/edit?id=<?= (int) $row['id'] ?>">Edit</a>
                                                <a href="#" class="btn-delete" data-id="<?= (int) $row['id'] ?>">Delete</a>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                <?php } else { ?>
                                    <tr>
                                        <td colspan="6" class="dish-empty">No dishes to be displayed...</td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                    <!-- Pagination -->
                    <?php if(!empty($dishes)) { ?>
                        <div id="pagination">
                            <ul>
                                <li class="page-item <?= ($page_no <= 1) ? "disabled" : "" ?>"> 
                                    <a href="<?= ($page_no > 1) ? '?page_no='.$pagy['previous_page'] : '' ?>" class="page-link">Previous</a>
                                </li>
                                <!-- Page numbers -->
                                <?php for ($counter = 1; $counter <= $pagy['total_no_of_pages']; $counter++) { ?>
                                    <?php if ($counter == $page_no) { ?>
                                        <li class="page-item"><a class="page-link active"> <?= $counter ?> </a></li>
                                    <?php } else { ?>
                                        <li class="page-item"><a href='?page_no=<?=$counter?>' class="page-link"><?= $counter ?></a></li>
                                    <?php } ?>
                                <?php } ?>
                                <!-- Next and last button -->
                                <?php if($page_no < $pagy['total_no_of_pages']) { ?>
                                    <li class="page-item <?= ($page_no >= $pagy['total_no_of_pages']) ? "disabled" : "" ?>">
                                        <a href="<?= ($page_no < $pagy['total_no_of_pages']) ?  "?page_no=".$pagy['next_page'] : ""?>" class="page-link"> Next  &rsaquo;&rsaquo; </a>
                                    </li>
                                    <li class="page-item"><a href="?page_no=<?=$pagy['total_no_of_pages']?>" class="page-link">Last</a></li>
                                <?php } ?>
                            </ul>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </section>
    </main>
<?php include "layouts/_footer.php"; ?>