<?php
  $userRole=Auth::user()->role;
  if($userRole==1){
  ?>
    @include('partials/left_eci')
  <?php
  }

  if($userRole==2){
  ?>
    @include('partials/left_ceo')
  <?php
  }
  if($userRole==3){
  ?>

    @include('partials/left_deo')

  <?php
  }
  if($userRole==4){
  ?>

    @include('partials/left_ro')

  <?php
  }
?>