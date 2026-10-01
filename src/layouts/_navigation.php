<nav class="container">
    <div id="logo">
        <a href="/employee/account">
            <img src="/assets/img/logo.png" alt="Food festive">
        </a>
    </div>
    <ul id="menu">
        <?php if(!isset($_SESSION['id'])) { ?>
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
                <a href="tel:+1234567890" class="btn btn-md btn-rounded">Call Us: +1 234 567 890</a>
            </li>
        <?php } else { ?>
            <li>
                <div class="dropdown">
                    <button class="dropdown-btn"><?= $_SESSION['name'] ?></button>
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