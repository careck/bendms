<div class="row">
  <div class="small-6 large-2 columns"></div>
  <div class="small-6 large-8 columns">
      <h2 align="center">Bend Management System</h2>
  </div>
  <div class="small-12 large-2 columns"></div>
</div>
<div class="row">
  <div class="small-6 large-2 columns"></div>
  <div class="small-6 large-8 columns">
      <a href="/bend-workhours/index" class="button expand large">Workhours</a>
  </div>
  <div class="small-12 large-2 columns"></div>
</div>
<div class="row">
  <div class="small-6 large-2 columns"></div>
  <div class="small-6 large-8 columns">
      <a href="/bend-electricity/index" class="button expand large">Electricity</a>
  </div>
  <div class="small-12 large-2 columns"></div>
</div>
<?php if ($w->Auth->hasRole("bend_admin")):?>
<div class="row">
  <div class="small-6 large-2 columns"></div>
  <div class="small-6 large-8 columns">
      <a href="/bend/admin" class="button expand large">Administration</a>
  </div>
  <div class="small-12 large-2 columns"></div>
</div>
<?php endif;?>
