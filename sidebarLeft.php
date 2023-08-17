<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    
<div class="sidebar" id="sidebar">
<button id="sidebarToggle" class="sidebarOpenButton"></button>
    <a href="/"><i class="fas fa-home"></i> </a>
    <a href="#notifications"><i class="fas fa-bell"></i> </a>
    <a href="profile.php"><i class="fas fa-user"></i> </a>
    <a href="userSetting.php"><i class="fas fa-cog"></i> </a>
    <a href="logout.php"><i class="fas fa-sign-out-alt"></i></a>
</div>

    <style>
        .sidebar {
            width: 50px;
            position: fixed;
            top: 50%;
            transform: translateY(-50%);
            left: -55px;
            background-color: #111;
            padding-top: 20px;
            display: flex;
            flex-direction: column;
            align-items: center;
            color: white;
            transition: left 0.3s;
            z-index: 3;
        }
        .sidebarOpenButton{
            position: absolute;
            width: 100px;
            transform: translate(0px, -43px);
            border-radius: 0px 50px 50px 0px;
            background-color: #111111;
        }
        .sidebar a {
            padding: 8px;
            text-decoration: none;
            color: white;
        }

        .sidebar a:hover {
            background-color: #575757;
        }

        /* Styles for hiding/showing the sidebar on mobile */
        @media screen and (max-width: 750px) {
            
        }

        /* Push the content when the sidebar is visible */
        .webContent {
            transition: margin-left 0.3s;
        }

        .sidebar-visible {
            left: 0;
        }

        .webContentMove {
            margin-left: 250px;
        }
    </style>

    <script>
        const sidebar = document.getElementById('sidebar');
        const sidebarToggle = document.getElementById('sidebarToggle');
        //const webContent = document.getElementById('webContent');

        sidebarToggle.addEventListener('click', () => {
            sidebar.classList.toggle('sidebar-visible');
            //webContent.classList.toggle('webContentMove');
        });
        window.addEventListener('click', (event) => {
            if (!sidebar.contains(event.target) && !sidebarToggle.contains(event.target)) {
                sidebar.classList.remove('sidebar-visible');
                //webContent.classList.remove('webContentMove');
            }
            else{
                sidebar.classList.add('sidebar-visible');
                //webContent.classList.add('webContentMove');
            }
        });
    </script>
