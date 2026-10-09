<nav class="container">
    <div id="logo">
        <a href="<?= isset($_SESSION['id']) ? '/account' : '/login' ?>">
            <img src="/assets/img/logo.png" alt="Food festive">
        </a>
    </div>
    <ul id="menu">
        <?php if (!empty($auth_page)) { ?>
            <li>
                <?php if ($auth_page === 'register') { ?>
                    <span>Already a member?</span>
                    <a href="/login" class="btn btn-sm btn-rounded">Log in</a>
                <?php } else { ?>
                    <span>New to Food Festive?</span>
                    <a href="/register" class="btn btn-sm btn-rounded">Create account</a>
                <?php } ?>
            </li>
        <?php } elseif (!isset($_SESSION['id'])) { ?>

            <li>
                <a href="#about" class="nav-link active">About Us</a>
            </li>

            <li>
                <a href="#services" class="nav-link">Popular Dishes</a>
            </li>

            <li>
                <a href="#services" class="nav-link">Services</a>
            </li>

            <li class="btn-call-out">
                <a href="tel:+1234567890" class="btn btn-md btn-rounded">
                    Call Us: +1 234 567 890
                </a>
            </li>

        <?php } else { ?>

            <li>
                <a href="/order" class="nav-link">Order</a>
            </li>

            <li>
                <div class="dropdown">
                    <button class="dropdown-btn">
                        <?= htmlspecialchars($_SESSION['name']) ?>
                    </button>

                    <div id="drop-down-list" class="dropdown-content">
                        <a href="#">Settings</a>
                        <a href="#">Profile</a>
                        <a href="/logout?logout=true">Logout</a>
                    </div>
                </div>
            </li>

        <?php } ?>
    </ul>
</nav>