<style>
  .tooltip:after,
[data-tooltip]:after {width: 230px}
</style>
<div class="heading text-cl-primary padding15">
    <h1 class="margin0">Download Recording</h1>
</div>

<div class="box b-f3-1">
  <div class="content padding15">
    <div class="col-md-12">
      <p><?php echo $note; ?></p>
      <?php if(@$downloadurl != NULL){ ?>
      <a href="<?php echo $downloadurl ?>" target="_blank" class="pure-button btn-medium btn-expand-tiny btn-white">
        <div class="prelative tooltip-bottom" data-tooltip="Disable any download manager to watch">
          Watch Recording
        </div>
      </a>
      <a href="<?php echo $downloadurl ?>" target="_blank" class="pure-button btn-medium btn-expand-tiny btn-white" download>Download Recording</a>
      <?php }else{ }?>
    </div>
  </div>
</div>