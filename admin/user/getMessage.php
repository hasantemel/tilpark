<?php if ( isset($_GET['session_id']) AND isset($_GET['get_message']) AND isset($_GET['query_message']) ) { session_id($_GET['session_id']); } else { exit; } ?>
<?php include ('../../tilpark.php'); ?>
<?php if ( !is_login() AND empty($_GET['get_message']) AND empty($_GET['query_message']) ) { exit; } 

if ( $_GET['get_message'] == "old" AND isset($_GET['list_old_message']) ) {
  if ( $get_message = get_message($_GET['list_old_message'], array('type' => 'mess-reply')) ) :
  $get_query = " (rec_u_id = '". get_active_user('id') ."' OR sen_u_id = '". get_active_user('id') ."' ) AND (top_id='". $_GET['query_message'] ."' OR id='". $_GET['query_message'] ."') AND date < '". $get_message->date ."' ORDER BY date DESC, id DESC LIMIT 3";
  ?>
  <?php if($messages = array_reverse(get_messages(array('query'=>$get_query)))): ?>
    <?php foreach($messages as $message): ?>
      <div class="row space-5 message-<?php echo $message->id; ?>" id="<?php echo $message->id; ?>">
        <?php if(get_active_user('id') != $message->sen_u_id): ?>
          <div class="col-md-1">
            <img src="<?php echo get_user_info($message->sen_u_id, 'avatar'); ?>" class="img-responsive br-3 pull-right" width="48">
          </div> <!-- /.col-md-1 -->
        <?php endif; ?>

        <div class="col-md-11">
          <div class="well padding-10 br-3">
            <div class="text-muted fs-11 italic">
              <span class="bold"><?php echo get_user_info($message->sen_u_id, 'name'); ?> <?php echo get_user_info($message->sen_u_id, 'surname'); ?></span> tarafından <span class="bold" data-toggle="tooltip" title="<?php echo substr($message->date,0,16); ?>"><?php echo get_time_late($message->date); ?></span> önce gönderildi.
            </div>
            <?php echo $message->message; ?>
          </div><!--/ .well /-->
          <div class="h-10"></div>
        </div> <!-- /.col-md-11 -->

        <?php if(get_active_user('id') == $message->sen_u_id): ?>
          <div class="col-md-1">
            <img src="<?php echo get_user_info($message->sen_u_id, 'avatar'); ?>" class="img-responsive br-3 pull-left" width="48">
          </div> <!-- /.col-md-1 -->
        <?php endif; ?>
      </div> <!-- /.row -->
    <?php endforeach; ?>
  <?php endif; ?>
<?php endif; 

} elseif ( $_GET['get_message'] == "new" AND isset($_GET['last_view_message']) ) {
  $get_query = "type='mess-reply' AND id > '". $_GET['last_view_message'] ."' AND top_id='". $_GET['query_message'] ."' ORDER BY date_update DESC, id DESC LIMIT 5"; 
  ?>
  <?php if ( $messages = get_messages(array('query'=>$get_query))): ?>
    <?php foreach($messages as $message): ?>
      <?php db()->query("UPDATE ".dbname('messages')." SET read_it='1' WHERE id='".$message->top_id."' "); ?>
      <til>
        <div class="row space-5 message-<?php echo $message->id; ?>" id="<?php echo $message->id; ?>" title="<?php echo $message->title; ?>" username="<?php echo get_user_info($message->sen_u_id, 'name'); ?> <?php echo get_user_info($message->sen_u_id, 'surname'); ?>">
          <?php if(get_active_user('id') != $message->sen_u_id): ?>
            <div class="col-md-1">
              <img src="<?php echo get_user_info($message->sen_u_id, 'avatar'); ?>" class="img-responsive br-3 pull-right" width="48">
            </div> <!-- /.col-md-1 -->
          <?php endif; ?>

          <div class="col-md-11">
            <div class="well padding-10 br-3">
              <div class="text-muted fs-11 italic">
                <span class="bold"><?php echo get_user_info($message->sen_u_id, 'name'); ?> <?php echo get_user_info($message->sen_u_id, 'surname'); ?></span> tarafından <span class="bold" data-toggle="tooltip" title="<?php echo substr($message->date,0,16); ?>"><?php echo get_time_late($message->date); ?></span> önce gönderildi.
              </div>
              <?php echo $message->message; ?>
            </div><!--/ .well /-->
            <div class="h-10"></div>
          </div> <!-- /.col-md-11 -->

          <?php if(get_active_user('id') == $message->sen_u_id): ?>
            <div class="col-md-1">
              <img src="<?php echo get_user_info($message->sen_u_id, 'avatar'); ?>" class="img-responsive br-3 pull-left" width="48">
            </div> <!-- /.col-md-1 -->
          <?php endif; ?>
        </div> <!-- /.row -->
      </til>
    <?php endforeach; ?>
  <?php endif;
 } else { return false; }
?>