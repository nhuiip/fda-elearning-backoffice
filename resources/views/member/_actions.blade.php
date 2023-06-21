<div class="btn-group">
    <button type="button" class="btn btn-dark btn-sm dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown"
        aria-expanded="false">
        <span class="visually-hidden">Toggle Dropdown</span>
    </button>
    <ul class="dropdown-menu">
        <li><a class="dropdown-item" href="javascript:;" data-url="{{ route('sendMail', $id) }}" onclick="sendMail(this)"><i class="fa fa-lock"></i>&nbsp; Send Password</a>
        <li>
            <hr class="dropdown-divider">
        </li>
        <li><a class="dropdown-item text-danger" href="javascript:;" data-text="Delete!"
                data-form="delete-form-{{ $id }}" onclick="fncDelete(this)"><i class="fa fa-remove"></i>&nbsp;
                Delete</a></li>
    </ul>
</div>
<form id="delete-form-{{ $id }}" method="post" action="{{ route('questions.destroy', $id) }}">
    @csrf
    @method('DELETE')
</form>
