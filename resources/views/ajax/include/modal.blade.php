<div class="modal fade" id="{{$id}}Modal" tabindex="-1" role="dialog" aria-labelledby="{{$id}}ModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="{{$id}}ModalLabel">{{$title}}</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        @yield($modalBody)
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" id="{{$id}}Moneda" class="btn btn-primary">{{$actionButton}}</button>
      </div>
    </div>
  </div>
</div>