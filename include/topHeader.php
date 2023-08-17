<div class="top-header">
    <div class="logo">
        <!-- Add your logo image or text here -->
        <img src="logo.png" alt="Logo">
    </div>
    <div class="search">
        <input type="text" placeholder="Search...">
    </div>
    <div class="user-panel">
        <?php echo $user['username']; ?>
        <img src="<?php echo $user['avatar']; ?>" class="avatar-image" alt="Your Avatar">
    </div>
</div>
<style>
/* Top Header Styles */
.top-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    background-color: #222;
    color: #fff;
    padding: 10px;
    height: 40px;
    border-top-left-radius: 0px;
    border-top-right-radius: 0px;
  }
  .top-header div{
    background-color: transparent;
    margin:0px;
  }
  .logo{
    display: flex;
    align-items: center;
  }
  .logo img {
    width: 40px; /* Adjust the size of the logo */
  }
  
  .search input {
    width: 200px; /* Adjust the size of the search input */
    padding: 8px;
    font-size: 16px;
    border: none;
    border-radius: 5px;
    transform: translateX(40px);
  }
  
  .user-panel {
    text-align: right;
    display: flex;
    align-items: center;
    height: 100%;
  }
  
  .avatar-image {
    width: 40px; /* Adjust the size of the user avatar */
    border-radius: 50%;
    margin-left: 10px;
  }
  @media only screen and (max-width: 650px) {
    .search input{
      width:100px;
      transform: translateX(0px);
    }
  }

</style>
