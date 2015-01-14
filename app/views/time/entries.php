<?php
/*
*********jason comments
* craig, tsk tsk, you diden't leave any
*
* changes made to javascript: added second div functions for modify button, added helper function to set data in said div
*
* and in the main html, added the div noted above.
***
*while poor practice, adding some php logic to this document to assist in displaying times.
*
*
*
*/ ?>
<?php //takes in unix timestamps, divides out to minutes and hours.
function Duration($startTime, $endTime) {
  $minute = $endTime - $startTime;
  $hours = floor( $minute / 3600 );
  $minute = floor(($minute - $hours * 3600) / 60);
  return $hours . ':' . $minute;
} ?>
<div class="row">
	<div class="col-sm-8 col-sm-offset-2">
		<table class="table table-striped">
			<thead>
				<tr>
					<th>Date</th>
					<th>Start Time</th>
					<th>End Time</th>
					<th>Duration</th>
					<th>Options</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($entries as $entry) { ?> 
				<tr>
					<td><?= $entry->startDate; ?></td>
					<td><?= $entry->startTime; ?></td>
					<td><?= $entry->endTime; ?></td>
					<td><?= Duration(strtotime($entry->startDate . $entry->startTime), strtotime($entry->endDate . $entry->endTime)); ?></td>
					<td>
					    <?php if ($current){ ?>
						<button class="btn btn-warning" data-toggle="modal" data-target="#timeModify" onclick='setId(<?= $entry->id . ', "' . $entry->startDate . '", "' . $entry->startTime  . '", "' . $entry->startDate . '", "' . $entry->endTime . '"' ; ?>)'>Modify</button>
						<a href="<?php echo URL::to('/time/entries/delete/' . $entry->id); ?>" class="btn btn-danger">Delete</a>
					    <?php } else { ?> Locked <?php } ?>
					</td>
				</tr>
				<?php } ?>
			</tbody>
		</table>
	</div>
</div>
<div class="row">
	<div class="col-sm-8 col-sm-offset-2">
		<?php if ($current) { ?> <button class="btn btn-success" data-toggle="modal" data-target="#timeEntry">Add Time Entry</button> <?php } ?>
	</div>
</div>

<div class="modal fade" id="timeEntry">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title">Add Time Entry</h4>
      </div>
      <form role="form" method="post" action="<?php echo URL::to('time/entries/create'); ?>" >
      <div class="modal-body">
      	<div class="form-group">
        	<label for="start_time">Category</label>
        	<select name="category_id" class="form-control">
        		<?php foreach ($categories as $category) { ?>
        		<option value="<?= $category->id; ?>"><?= $category->category; ?></option>
        		<?php } ?>
        	</select>
        </div>
        <div class="form-group">
        	<label for="start_date">Start Date</label>
        	<input type="test" id="start_date" class="form-control" name="start_date" />
        </div>
        <div class="form-group">
        	<label for="start_time">Start Time</label>
        	<input type="test" id="start_time" class="form-control" name="start_time" />
        </div>
        <div class="form-group">
        	<label for="end_date">End Date</label>
        	<input type="test" id="end_date" class="form-control" name="end_date" />
        </div>
        <div class="form-group">
        	<label for="end_time">End Time</label>
        	<input type="test" id="end_time" class="form-control" name="end_time" />
        </div> 
       <div class="form-group">
        	<label for="clock_in">Clocked In</label>
        	<input type="checkbox" id="clock_in" class="form-control" name="clock_in" value="yes" checked />
        </div>
       <div class="form-group">
        	<label for="description">Comments</label>
        	<textarea class="form-control" name="description"></textarea>
        </div>
        <input type="hidden" name="pay_id" value="<?php echo $pay_id; ?>" />
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <input type="submit" name="submit" value="Add Time" class="btn btn-primary" />
      </div>
      </form>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->



<div class="modal fade" id="timeModify">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title">Modify Entry</h4>
      </div>
      <form role="form" method="post" action="<?php echo URL::to('time/entries/modify-time'); ?>" >
	<input type="hidden" id="ModifyId" name="id" value="" />
      <div class="modal-body">
      	<div class="form-group">
        	<label for="start_time">Category</label>
        	<select name="category_id" class="form-control">
        		<?php foreach ($categories as $category) { ?>
        		<option value="<?= $category->id; ?>"><?= $category->category; ?></option>
        		<?php } ?>
        	</select>
        </div>
        <div class="form-group">
        	<label for="modify_start_date">Start Date</label>
        	<input type="test" id="modify_start_date" class="form-control" name="modify_start_date" />
        </div>
        <div class="form-group">
        	<label for="modify_start_time">Start Time</label>
        	<input type="test" id="modify_start_time" class="form-control" name="modify_start_time" />
        </div>
        <div class="form-group">
        	<label for="modify_end_date">End Date</label>
        	<input type="test" id="modify_end_date" class="form-control" name="modify_end_date" />
        </div>
        <div class="form-group">
        	<label for="modify_end_time">End Time</label>
        	<input type="test" id="modify_end_time" class="form-control" name="modify_end_time" />
        </div>
       <div class="form-group">
        	<label for="modify_clock_in">Clocked In</label>
        	<input type="checkbox" id="modify_clock_in" class="form-control" name="modify_clock_in" value="yes" checked />
        </div>
        <div class="form-group">
        	<label for="description">Comments</label>
        	<textarea class="form-control" name="description"></textarea>
        </div>
        <input type="hidden" name="pay_id" value="<?php echo $pay_id; ?>" />
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <input type="submit" name="submit" value="Change Time" class="btn btn-primary" />
      </div>
      </form>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<script>
function setId(id, startDate, startTime, endDate, endTime)
{
  document.getElementById("ModifyId").value = id;
  document.getElementById("modify_start_date").value = startDate;
  document.getElementById("modify_start_time").value = startTime;
  document.getElementById("modify_end_date").value = endDate;
  document.getElementById("modify_end_time").value = endTime;
}

$(function() {
  $('#start_date').datetimepicker({
    timepicker: false,
    format: 'Y-m-d',
    todayButton: true,
  });
  $('#start_time').datetimepicker({
    datepicker: false,
    step: 5,
    format: 'H:i',
  });
  $('#end_date').datetimepicker({
    timepicker: false,
    format: 'Y-m-d',
    todayButton: true,
  });
  $('#end_time').datetimepicker({
    datepicker: false,
    step: 5,
    format: 'H:i',
  });


  $('#modify_start_date').datetimepicker({
    timepicker: false,
    todayButton: true,
    format: 'Y-m-d',
  });
  $('#modify_start_time').datetimepicker({
    datepicker: false,
    step: 5,
    format: 'H:i',
  });
  $('#modify_end_date').datetimepicker({
    timepicker: false,
    format: 'Y-m-d',
    todayButton: true,
  });
  $('#modify_end_time').datetimepicker({
    datepicker: false,
    step: 5,
    format: 'H:i',
  });
})
</script>
