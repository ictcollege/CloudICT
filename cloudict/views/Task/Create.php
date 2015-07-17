<?php $this->load->helper("form"); ?>

<div id="CreateTask">

        <?php echo form_open("Tasks/store"); ?>
        Task Name: <input type="text" name="TaskName" /> <br />
        Task Description:  <input type="text" name="TaskDescription" /> <br />
<!--        Expire Date: <input type="date" name="TaskExpireDate" /> <br />-->
        Expire Date: <input type="text" name="TaskExpireDate" /> <br />
        Assign task as Group Task? <input type="checkbox" name="isGroupTask" /> <br /> <br />

        Assign Users: <br /><br />
        <?php
            foreach($Groups as $Group => $Users){
                echo "Group Name: $Group <br />";
                foreach($Users as $User => $UserData){
                    echo "Username: $User"."<input type='checkbox' name='Users' value='{$UserData['UserId']}' /> <br />";
                }
                echo "<br />";
            }
        ?>
        <input type="submit" value="Create Task" />
    <?php echo form_close() ?>
</div>

<div id="errors"></div>