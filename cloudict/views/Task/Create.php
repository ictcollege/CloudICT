<div id="CreateTask">
    <form id="createTask" action="Tasks/store">

        Task Name: <input type="text" name="TaskName" /> <br />
        Task Description:  <input type="text" name="TaskDescription" /> <br />
        Expire Date: <input type="date" name="TaskExpireDate" /> <br />
        Assign task as Group Task? <input type="checkbox" name="isGroupTask" /> <br /> <br />

        Assign Users: <br />
        <span>Dul92</span> <input type='checkbox' name='assignedUsers' value='username' /> <br />
        <?php
//            $users = null;
//            foreach($Groups as $user){
//                $username = $user['username'];
//                $users = "<p>$username</p> <input type='checkbox' name='assignedUsers' value='$username' /> <br /> ";
//            }
//            echo($users);
        ?>
        <input type="submit" value="Create Task" />
    </form>
</div>

<div id="errors"></div>