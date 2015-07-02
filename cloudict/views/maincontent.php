<div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <div class="route pull-left">
                    <i class="fa fa-home fa-2x homeicon"></i>
                    <i class="fa fa-angle-right fa-2x separatoricon"></i>
                    <button type="button" class="btn btn-default routebutton"><i class="fa fa-upload  fa-1x"></i></button>
                    <button type="button" class="btn btn-default routebutton">New</button>
                        
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4><i class="fa fa-files-o fa-fw"></i> All files</h4>
                    </div>
                    <div class="panel-body">
                         <div class="col-lg-12 table-responsive">
                    <table class="table table-striped tablefiles table-hover">
                        <thead>
                            <th></th>
                            <th><input type="checkbox" value=""></th>
                            <th>Name</th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th>Size</th>
                            <th>Modified</th>
                        </thead>
                        <tbody>
                            <tr>
                                <td style="width:50px" align="left"><i class="fa  fa-star-o fa-fw fileiconhide"></td>
                                <td style="width:50px"></td>
                                <td style="width:50px" class="filename">Test.txt</td>
                                <td style="width:50px" align="left"><i class="fa  fa-pencil fa-fw fileiconhide"></i></td>
                                <td style="width:50px"><i class="fa  fa-cloud-download fa-fw fileiconhide"></i></td>
                                <td style="width:50px"><i class="fa  fa-share-alt fa-fw fileiconhide"></i></td>
                                <td style="width:50px"><i class="fa  fa-trash-o fa-fw fileiconhide"></i></td>
                                <td style="width:50px">8 kB</td>
                                <td style="width:50px">last year</td>
                            <tr>
                           <tr>
                                <td style="width:50px" align="left"><i class="fa  fa-star-o fa-fw fileiconhide"></td>
                                <td style="width:50px"></td>
                                <td style="width:50px" class="filename">Test.txt</td>
                                <td style="width:50px" align="left"><i class="fa  fa-pencil fa-fw fileiconhide"></i></td>
                                <td style="width:50px"><i class="fa  fa-cloud-download fa-fw fileiconhide"></i></td>
                                <td style="width:50px"><i class="fa  fa-share-alt fa-fw fileiconhide"></i></td>
                                <td style="width:50px"><i class="fa  fa-trash-o fa-fw fileiconhide"></i></td>
                                <td style="width:50px">8 kB</td>
                                <td style="width:50px">last year</td>
                            <tr>
                            <tr>
                                <td style="width:50px" align="left"><i class="fa  fa-star-o fa-fw fileiconhide"></td>
                                <td style="width:50px"></td>
                                <td style="width:50px" class="filename">Test.txt</td>
                                <td style="width:50px" align="left"><i class="fa  fa-pencil fa-fw fileiconhide"></i></td>
                                <td style="width:50px"><i class="fa  fa-cloud-download fa-fw fileiconhide"></i></td>
                                <td style="width:50px"><i class="fa  fa-share-alt fa-fw fileiconhide"></i></td>
                                <td style="width:50px"><i class="fa  fa-trash-o fa-fw fileiconhide"></i></td>
                                <td style="width:50px">8 kB</td>
                                <td style="width:50px">last year</td>
                            <tr>


                        </tbody>
                    </table>
                </div>
                    </div>
                </div>
               
            </div>
            
            
        </div>
        <!-- /#page-wrapper -->

    </div>
    
    <?php
     $data['base_url'] = $base_url;
     $this->load->view('scripts.php', $data);
    ?>
    
</body>

</html>
