<div class="row">
        <div class="col-sm-6 col-sm-offset-3">
                <table class="table table-striped table-hover">
                        <thead>
                                <tr>
					<th>Name/Email</th>
					<th>Time</th>
				</tr>

                        </thead>
                        <tbody>
			  <?php foreach ($entrys as $i) { ?>
                                <tr>
					<td><a href="mailto:<?php echo $i['email']; ?> "><?php echo $i['name']; ?></a></td>
					<td>
					  <table class="table">
					    <tr>
					      <th></th>
					      <th>Sat</th>
					      <th>Sun</th>
					      <th>Mon</th>
					      <th>Tue</th>
					      <th>Wed</th>
					      <th>Thu</th>
					      <th>Fri</th>
					    </tr>
					    <tr>
					      <td><?php echo date('M-d', $dates['0']); ?></td>
					      <td><?php if (isset($i['week1']['sat'])) {
						foreach($i['week1']['sat'] as $j) {
						  if (!$j['clock']) { echo "<mark>"; }
						  echo date('g:iA', $j['start']) . '-' . date('g:iA', $j['end']) . "<br/>"; 
						  if (!$j['clock']) { echo "</mark>"; }
						  }
					  	 } ?>
					      </td>
					      <td><?php if (isset($i['week1']['sun'])) {
						foreach($i['week1']['sun'] as $j) {
						  if (!$j['clock']) { echo "<mark>"; }
						  echo date('g:iA', $j['start']) . '-' . date('g:iA', $j['end']) . "<br/>"; 
						  if (!$j['clock']) { echo "</mark>"; }
						  }
					  	 } ?>
					      </td>
					      <td><?php if (isset($i['week1']['mon'])) {
						foreach($i['week1']['mon'] as $j) {
						  if (!$j['clock']) { echo "<mark>"; }
						  echo date('g:iA', $j['start']) . '-' . date('g:iA', $j['end']) . "<br/>"; 
						  if (!$j['clock']) { echo "</mark>"; }
						  }
					  	 } ?>
					      </td>
					      <td><?php if (isset($i['week1']['tue'])) {
						foreach($i['week1']['tue'] as $j) {
						  if (!$j['clock']) { echo "<mark>"; }
						  echo date('g:iA', $j['start']) . '-' . date('g:iA', $j['end']) . "<br/>"; 
						  if (!$j['clock']) { echo "</mark>"; }
						  }
					  	 } ?>
					      </td>
					      <td><?php if (isset($i['week1']['wed'])) {
						foreach($i['week1']['wed'] as $j) {
						  if (!$j['clock']) { echo "<mark>"; }
						  echo date('g:iA', $j['start']) . '-' . date('g:iA', $j['end']) . "<br/>"; 
						  if (!$j['clock']) { echo "</mark>"; }
						  }
					  	 } ?>
					      </td>
					      <td><?php if (isset($i['week1']['thu'])) {
						foreach($i['week1']['thu'] as $j) {
						  if (!$j['clock']) { echo "<mark>"; }
						  echo date('g:iA', $j['start']) . '-' . date('g:iA', $j['end']) . "<br/>"; 
						  if (!$j['clock']) { echo "</mark>"; }
						  }
					  	 } ?>
					      </td>
 
					      <td><?php if (isset($i['week1']['fri'])) {
						foreach($i['week1']['fri'] as $j) {
						  if (!$j['clock']) { echo "<mark>"; }
						  echo date('g:iA', $j['start']) . '-' . date('g:iA', $j['end']) . "<br/>"; 
						  if (!$j['clock']) { echo "</mark>"; }
						  }
					  	 } ?>
					      </td>
					    </tr>

					    <tr>

					      <td><?php echo date('M-d', $dates['1']); ?></td>

					      <td><?php if (isset($i['week2']['sat'])) {
						foreach($i['week2']['sat'] as $j) {
						  if (!$j['clock']) { echo "<mark>"; }
						  echo date('g:iA', $j['start']) . '-' . date('g:iA', $j['end']) . "<br/>"; 
						  if (!$j['clock']) { echo "</mark>"; }
						  }
					  	 } ?>
					      </td>
					      <td><?php if (isset($i['week2']['sun'])) {
						foreach($i['week2']['sun'] as $j) {
						  if (!$j['clock']) { echo "<mark>"; }
						  echo date('g:iA', $j['start']) . '-' . date('g:iA', $j['end']) . "<br/>"; 
						  if (!$j['clock']) { echo "</mark>"; }
						  }
					  	 } ?>
					      </td>
					      <td><?php if (isset($i['week2']['mon'])) {
						foreach($i['week2']['mon'] as $j) {
						  if (!$j['clock']) { echo "<mark>"; }
						  echo date('g:iA', $j['start']) . '-' . date('g:iA', $j['end']) . "<br/>"; 
						  if (!$j['clock']) { echo "</mark>"; }
						  }
					  	 } ?>
					      </td>
					      <td><?php if (isset($i['week2']['tue'])) {
						foreach($i['week2']['tue'] as $j) {
						  if (!$j['clock']) { echo "<mark>"; }
						  echo date('g:iA', $j['start']) . '-' . date('g:iA', $j['end']) . "<br/>"; 
						  if (!$j['clock']) { echo "</mark>"; }
						  }
					  	 } ?>
					      </td>
					      <td><?php if (isset($i['week2']['wed'])) {
						foreach($i['week2']['wed'] as $j) {
						  if (!$j['clock']) { echo "<mark>"; }
						  echo date('g:iA', $j['start']) . '-' . date('g:iA', $j['end']) . "<br/>"; 
						  if (!$j['clock']) { echo "</mark>"; }
						  }
					  	 } ?>
					      </td>
					      <td><?php if (isset($i['week2']['thu'])) {
						foreach($i['week2']['thu'] as $j) {
						  if (!$j['clock']) { echo "<mark>"; }
						  echo date('g:iA', $j['start']) . '-' . date('g:iA', $j['end']) . "<br/>"; 
						  if (!$j['clock']) { echo "</mark>"; }
						  }
					  	 } ?>
					      </td>
 
					      <td><?php if (isset($i['week2']['fri'])) {
						foreach($i['week2']['fri'] as $j) {
						  if (!$j['clock']) { echo "<mark>"; }
						  echo date('g:iA', $j['start']) . '-' . date('g:iA', $j['end']) . "<br/>"; 
						  if (!$j['clock']) { echo "</mark>"; }
						  }
					  	 } ?>
					      </td>
					    </tr>
					    <tr>
					    <td colspan="8">
					      <?php if (isset($i['comment'])) {
						foreach ( $i['comment'] as $j ) {
						  echo date('M-d', $j['date']) . ": " . $j['comment'] . "<br/>";
						} } ?>
					    <td>
					  </table>
					</td>
				</tr>
			  <?php } ?>
                        </tbody>
                </table>
        </div>
</div>

