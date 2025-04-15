<?php
// Sidebar.php
?>
<link rel="stylesheet" href="../public/admin/css/sidebar.css">

<div class="sidebar">
    <div class="logo">
        <h4>Admin Dashboard</h4>
    </div>
    <a href="/Movie_Project/Movie/manageMovie" class="active"><i class="fas fa-film"></i><span>Manage Movies</span></a>
    <a href="/Movie_Project/Genre/manage" data-page="manage-genres"><i class="fas fa-tags"></i><span>Manage Genres</span></a>
    <a href="/Movie_Project/Actor/manage" data-page="manage-actors"><i class="fas fa-users"></i><span>Manage Actors</span></a>
    <a href="/Movie_Project/Login/logout" data-page="logout"><i class="fas fa-sign-out-alt"></i><span>Logout</span></a>
</div>

<script>
    // Function to set the active link based on the current URL
    function setActiveLink() {
        const currentPath = window.location.pathname;
        const links = document.querySelectorAll('.sidebar a');

        links.forEach(link => {
            const linkPath = link.getAttribute('href');
            link.classList.remove('active'); // Remove active class from all links

            // Check if the current path matches the link's href
            if (currentPath === linkPath || (currentPath === '/admin' && linkPath === '/admin')) {
                link.classList.add('active');
            }
        });
    }

    // Run on page load to set the active link
    document.addEventListener('DOMContentLoaded', setActiveLink);

    // Add click event listeners to update the active link when clicked
    document.querySelectorAll('.sidebar a').forEach(link => {
        link.addEventListener('click', function() {
            // Remove active class from all links
            document.querySelectorAll('.sidebar a').forEach(l => l.classList.remove('active'));
            // Add active class to the clicked link
            this.classList.add('active');
        });
    });
</script>