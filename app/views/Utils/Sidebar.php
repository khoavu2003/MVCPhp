<style>
    .sidebar {
    width: 250px;
    height: 100vh;
    background-color: #333;
    color: white;
    position: fixed;
    top: 0;
    left: 0;
    padding-top: 20px;
}

.sidebar a {
    color: white;
    padding: 15px;
    text-decoration: none;
    display: block;
}

.sidebar a:hover {
    background-color: #444;
}

.main-content {
    margin-left: 260px;
    padding: 20px;
}

.header {
    background-color: #222;
    color: white;
    padding: 10px;
}

.table th,
.table td {
    text-align: center;
}

.pagination {
    display: flex;
    justify-content: center;
    margin-top: 20px;
}
</style>
<div class="sidebar">
        <div class="p-3">
            <h4>Admin Dashboard</h4>
            <hr>
            <a href="/Movie_Project/Movie/manageMovie" >Manage Movies</a>
            <a href="/Movie_Project/Actor/manage" >Manage Actors</a>
            <a href="/Movie_Project/Genre/manage">Manage Genre</a>
            <a href="/Movie_Project/Login/logout">Logout</a>
        </div>
 </div>