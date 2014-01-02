<script type="text/html" id="user-form">
    <div class="form-horizontal">
        <div class="form-group">
            <label class="col-sm-3 control-label">Username</label>
            <div class="col-sm-9">
                <input class="form-control" data-bind="nowValue: username" />
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-3 control-label">Password</label>
            <div class="col-sm-9">
                <input class="form-control" data-bind="nowValue: password" />
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-3 control-label">Email</label>
            <div class="col-sm-9">
                <input class="form-control" data-bind="nowValue: email" />
            </div>
        </div>
    </div>
</script>
<script type="text/html" id="group-form">
    <div class="form-horizontal">
        <div class="form-group">
            <label class="col-sm-3 control-label">Name</label>
            <div class="col-sm-9">
                <input class="form-control" data-bind="nowValue: name" />
            </div>
        </div>
    </div>
</script>
<script type="text/html" id="role-form">
    <div class="form-horizontal">
        <div class="form-group">
            <label class="col-sm-3 control-label">Name</label>
            <div class="col-sm-9">
                <input class="form-control" data-bind="nowValue: name" />
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-3 control-label">Filter</label>
            <div class="col-sm-9">
                <select class="form-control" data-bind="value: filter">
                    <option value="">None</option>
                    <option value="A">All Access</option>
                    <option value="D">No Access</option>
                    <option value="R">Revoke Permissions</option>
                </select>
            </div>
        </div>
    </div>
</script>
<script type="text/html" id="permission-form">
    <div class="form-horizontal">
        <div class="form-group">
            <label class="col-sm-3 control-label">Area</label>
            <div class="col-sm-9">
                <input class="form-control" data-bind="nowValue: area" />
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-3 control-label">Permission</label>
            <div class="col-sm-9">
                <input class="form-control" data-bind="nowValue: permission" />
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-3 control-label">Description</label>
            <div class="col-sm-9">
                <input class="form-control" data-bind="nowValue: description" />
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-3 control-label">Actions</label>
            <div class="col-sm-9">
                <input class="form-control" data-bind="nowValue: entered_actions" />
            </div>
        </div>
    </div>
</script>