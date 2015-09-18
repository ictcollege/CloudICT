<ul class="media-list">

    <li class="media">

        <div class="media-body">
      
            <?php foreach($Messages as $Message){?>
            <?php  
            // var_dump($Session['userid']);exit(); 
                        if($Session['userid']==$Message['IdSender']){
             ?>
            <div class="media">
                <a class="pull-left" href="#">
                </a>
                <div class="media-body" style="border:solid 2px #3498DB;border-radius:5px" >
                    <?php echo $Message['Message']; ?>
                    <br />
                    <small class="text-muted"> <?php echo $Message['Sender']; ?> |  <?php echo date('H:i d M Y',$Message['Time']);?></small>
                    <hr />
                </div>
            </div>
            <?php}
                else{ ?>
                <div class="media">
                <a class="pull-left" href="#">
                </a>
                <div class="media-body" >
                    <?php echo $Message['Message']; ?>
                    <br />
                    <small class="text-muted"> <?php echo $Message['Sender']; ?> |  <?php echo date('H:i d M Y',$Message['Time']);?></small>
                    <hr />
                </div>
            </div>
             <?php    }
             } ?>

        </div>
    </li>

</ul>
