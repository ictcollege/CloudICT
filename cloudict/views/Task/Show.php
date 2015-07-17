<a href="<?php echo $base_url ?>Tasks/create">Create Task</a>
<div id="ShowTasks">
    <div id="assigned">
        <?php
            $assign = null;
            foreach($assigned as $task){
                $taskName = $task['TaskName'];
                $assign = "<h5><a href='$base_url.Tasks/edit'>$taskName</a></h5>";
            }
            echo $assign;
        ?>
    </div>
    <div id="given">
        <?php
            $assign = null;
            foreach($given as $task){
                $taskName = $task['TaskName'];
                $assign = "<h5><a href=".$base_url."Tasks/edit>$taskName</a></h5>";
            }
            echo $assign;
        ?>

    </div>
</div>

<div id="errors"></div>