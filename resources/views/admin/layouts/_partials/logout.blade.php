<div id="Mlogout" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="Mlogout" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="logout">Ready to Leave?</h5>
        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">×</span>
        </button>
      </div>
      <div class="modal-footer">
        <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
        <form method="post" action="{{ route('logout') }}">
          @csrf
          <button type="submit" class="btn btn-primary"> Logout </button>
        </form>
      </div>
    </div>
  </div>
</div>
