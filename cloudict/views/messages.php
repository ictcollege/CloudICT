<ul class="media-list">

    <li class="media">

        <div class="media-body">
            <?php foreach($Messages as $Message){?>
            <div class="media">
                <a class="pull-left" href="#">
                    <img class="media-object img-circle " src="<?php echo base_url();?>public/img/user.png" />
                </a>
                <div class="media-body" >
                    <?php echo $Message['Message']; ?>
                    <br />
                    <small class="text-muted"> <?php echo $Message['Sender']; ?> |  <?php echo date('H:i d M Y',$Message['Time']);?></small>
                    <hr />
                </div>
            </div>
            <?php } ?>

        </div>
    </li>

</ul>
