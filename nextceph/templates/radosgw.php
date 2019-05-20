<?php
script('nextceph', 'script');
style('nextceph', 'style');
?>

<div id="app">
        <div id="app-navigation">
                <?php print_unescaped($this->inc('navigation/index')); ?>
                <?php print_unescaped($this->inc('settings/index')); ?>
        </div>

        <div id="app-content">
                <div id="app-content-wrapper">
                        <?php
                        function get($url){
                  				$ch = curl_init();
                  				curl_setopt($ch, CURLOPT_URL,$url);
                  				curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
                  				$result = curl_exec($ch);
                  				$data = json_decode($result);
                  				return $data;
                  			}
                  			$url = 'http://'.$_[4].':'.$_[5].'/rgw';
                  			$data = get($url);
                        echo '<div id="container"><main>';
                        echo '<pre><H1>Rados Object Gateway</H1><br>';
                        echo '<pre><h2>RGW Users</h2>';
                        echo '<a class="button" href="#addRGWUser">Add New RGW User</a><br><br>';
                        echo '<table><tr>';
                        echo '<th><b>User ID</b></th>';
                        echo '<th><b>Display Name</b></th>';
                        echo '<th><b>Access Key</b></th>';
                        echo '<th><b>Secret Key</b></th>';
                        echo '<th><b>Max Buckets</b></th>';
                        echo '<th><b>Capabilities</b></th>';
                        echo '<th><b>Suspended</b></th>';
                        echo '<th><b>Edit</b></th>';
                        echo '<th><b>Delete</b></th>';
                        echo '</tr>';
                        foreach ($data as $data){
                          echo '<tr>';
                          echo '<td>'.$data->user_id.'</td>';
                          echo '<td>'.$data->display_name.'</td>';
                          echo '<td>'.$data->keys[0]->access_key.'</td>';
                          echo '<td>'.$data->keys[0]->secret_key.'</td>';
                          echo '<td>'.$data->max_buckets.'</td>';
                          echo '<td>';
                          foreach ($data->caps as $caps){
                              echo $caps->type.' : '.$caps->perm.'<br>';
                          }
                          echo '</td>';
                          echo '<td>'.(boolval($data->suspended) ? 'true' : 'false').'</td>';
                          echo '<td><form action="#editRGWUser" method="get"><input name="uid" type="hidden" value="'.$data->user_id.'"></input><input name="displayname " type="hidden" value="'.$data->display_name.'"></input><input type="submit" class="icon-edit" value=""></input></form></td>';
                          echo '<td><form action="#delRGWUser" method="get"><input name="uid" type="hidden" value="'.$data->user_id.'"><input type="submit" class="icon-delete" value=""></input></form></td>';
                          echo '</tr>';
                        }
                        echo '</table><br>';
                        $url = 'http://'.$_[4].':'.$_[5].'/rgw/s3';
                  			$data = get($url);
                        echo '<pre><h2>RGW S3 Buckets</h2>';
                        echo '<a class="button" href="#addS3Bucket">Add New S3 Bucket</a><br><br>';
                        echo '<table><tr>';
                        echo '<td><b>User ID</b></td>';
                        echo '<td><b>Bucket Name</b></td>';
                        echo '<td><b>Bucket ID</b></td>';
                        echo '<td><b>Bucket Owner</b></td>';
                        echo '<td><b>Bucket Quota</b></td>';
                        echo '<td><b>Bucket Usage</b></td>';
                        echo '<td><b>Delete</b></td>';
                        echo '</tr>';
                        foreach ($data as $data){
                          $count = 0;
                          foreach ($data->buckets as $buckets){
                            if ($count == 0){
                							echo '<tr><td><b>'.$data->uid.'</b></td>';
                						} else {
                							echo '<tr><td></td>';
                						}
                            echo '<td>'.$buckets[0]->stats->bucket.'</td>';
                            echo '<td>'.$buckets[0]->stats->id.'</td>';
                            echo '<td>'.$buckets[0]->stats->owner.'</td>';
                            echo '<td>';
                            echo 'enabled : '.$buckets[0]->stats->bucket_quota->enabled.'<br>';
                            echo 'max_objects : '.$buckets[0]->stats->bucket_quota->max_objects.'<br>';
                            echo 'max_size_kb : '.$buckets[0]->stats->bucket_quota->max_size_kb.'<br>';
                            echo '</td>';
                            echo '<td>';
                            foreach ($buckets[0]->stats->usage as $key=>$value){
                              echo 'pool'.' : '.$key.'<br>';
                              foreach ($value as $key=>$value){
                                echo $key.' : '.$value.'<br>';
                              }
                            }
                            echo '</td>';
                            echo '<td><form action="#delS3Bucket" method="get"><input name="name" type="hidden" value="'.$buckets[0]->stats->bucket.'"><input type="submit" class="icon-delete" value=""></input></form></td>';
                            echo '</tr>';
                            $count++;
                          }
                        }
                        echo '</table>';
                        echo '</pre></main></div>';
                        ?>
                        <div id="addRGWUser" class="overlay">
                  				<div class="popup">
                  					<h2>Generate New RGW User</h2>
                  					<a class="close" href="#">&times;</a>
                  					<div class="content">
                  			      <form action="apply" method="POST">
                  							<input name="type" type="hidden" value="addRGWUser"></input>
                  			        User ID :<input type="text" name="uid"></input> <br>
                                Display Name :<input type="text" name="displayname"></input> <br>
                  							User Capabilities : <input type="text" name="usercaps"></input> <br>
                                Max Buckets :<input type="number" name="maxbuckets"></input> <br>
                                Suspend : <br>
                  							<select name="issuspended" size="10">
                                  <option value=0>false</option>
                  								<option value=1>true</option>
                  							</select><br>
                  			        <center><input type="submit" value="Generate"></input><a class="button" href="#">Cancel</a></center>
                  			      </form>
                  					</div>
                  				</div>
                  			</div>
                        <div id="addS3Bucket" class="overlay">
                  				<div class="popup">
                  					<h2>Generate New S3 Bucket</h2>
                  					<a class="close" href="#">&times;</a>
                  					<div class="content">
                  			      <form action="apply" method="POST">
                  							<input name="type" type="hidden" value="addS3Bucket"></input>
                  			        Bucket Name :<input type="text" name="name"></input> <br>
                                Access List :<input type="text" name="acl"></input> <br>
                  			        <center><input type="submit" value="Generate"></input><a class="button" href="#">Cancel</a></center>
                  			      </form>
                  					</div>
                  				</div>
                  			</div>
                        <div id="editRGWUser" class="overlay">
                  				<div class="popup">
                  					<h2>Edit RGW User <b><?php echo $_GET["uid"]?></b></h2>
                  					<a class="close" href="#">&times;</a>
                  					<div class="content">
                  			      <form action="apply" method="POST">
                  							<input name="type" type="hidden" value="editRGWUser"></input>
                  			        <input type="hidden" name="uid" value="<?php echo $_GET["uid"]?>"></input>
                                Display Name : <input type="text" name="displayname"></input> <br>
                  							User Capabilities : <input type="text" name="usercaps"></input> <br>
                                Max Buckets :<input type="number" name="maxbuckets"></input> <br>
                                Suspend : <br>
                  							<select name="issuspended" size="10">
                                  <option value=0>false</option>
                  								<option value=1>true</option>
                  							</select><br>
                  			        <center><input type="submit" value="Generate"></input><a class="button" href="#">Cancel</a></center>
                  			      </form>
                  					</div>
                  				</div>
                  			</div>
                        <div id="delRGWUser" class="overlay">
                  				<div class="popup">
                  					<h2>Delete RGW User <b><?php echo $_GET["uid"]?></b></h2>
                  					<a class="close" href="#">&times;</a>
                  					<div class="content">
                  			      <form action="apply" method="POST">
                  							<input name="type" type="hidden" value="delRGWUser"></input>
                                <input type="hidden" name="uid" value="<?php echo $_GET["uid"]?>"></input>
                  			        Are you sure to delete user <b><?php echo($_GET["uid"])?></b>?<br>
                  			        <center><input type="submit" value="Delete"></input><a class="button" href="#">Cancel</a></center>
                  			      </form>
                  					</div>
                  				</div>
                  			</div>
                        <div id="delS3Bucket" class="overlay">
                  				<div class="popup">
                  					<h2>Delete S3 Bucket <b><?php echo $_GET["name"]?></b></h2>
                  					<a class="close" href="#">&times;</a>
                  					<div class="content">
                  			      <form action="apply" method="POST">
                  							<input name="type" type="hidden" value="delS3Bucket"></input>
                                <input type="hidden" name="name" value="<?php echo $_GET["name"]?>"></input>
                  			        Are you sure to delete bucket <b><?php echo($_GET["name"])?></b>?<br>
                  			        <center><input type="submit" value="Delete"></input><a class="button" href="#">Cancel</a></center>
                  			      </form>
                  					</div>
                  				</div>
                  			</div>
                </div>
        </div>
</div>
