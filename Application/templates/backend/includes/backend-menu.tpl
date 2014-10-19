<nav id="main">
    <ul>
        <?php foreach ($menu_links as $menu_key => $menu_link): ?>
            <li class="<?= $menu_link['class']; ?>">
                <a href="<?= $menu_link['url']; ?>" title="<?= $menu_link['text']; ?>">
                    <?= $menu_link['text']; ?>
                </a>
            </li>
        <?php endforeach; ?>
    </ul>
</nav>