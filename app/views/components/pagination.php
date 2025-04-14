<?php
// components/pagination.php
if ($totalPages > 1):
?>
<div class="pagination">
    <ul>
        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
            <li><a href="index.php?page=<?php echo $i; ?>" class="<?php echo ($i == $page) ? 'active' : ''; ?>"><?php echo $i; ?></a></li>
        <?php endfor; ?>
    </ul>
</div>
<?php endif; ?>