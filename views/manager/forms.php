<script type="text/html" id="user-form">
    <div class="form-horizontal">
        <div class="form-group">
            <label class="col-sm-3 control-label">Username</label>
            <div class="col-sm-9">
                <input class="form-control" data-bind="immediate: username" />
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-3 control-label">Password</label>
            <div class="col-sm-9">
                <input class="form-control" data-bind="immediate: password" />
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-3 control-label">Email</label>
            <div class="col-sm-9">
                <input class="form-control" data-bind="immediate: email" />
            </div>
        </div>
    </div>
</script>
<script type="text/html" id="user-profile-form">
    <div class="pull-right">
        <button title="Add" class="btn btn-default" data-bind="click: add"><span class="glyphicon glyphicon-plus"></span></button>
        <button title="Delete" class="btn btn-default" data-bind="click: remove"><span class="glyphicon glyphicon-remove"></span></button>
        <button title="Select All" class="btn btn-default" data-bind="click: select_all"><span class="glyphicon glyphicon-check"></span> <span data-bind="text: selected_metadatas_count"></span></button>
    </div>
    <div class="clearfix"></div>
    <!-- ko foreach: metadatas -->
    <div class="form-inline">
        <div class="checkbox">
            <label><input type="checkbox" data-bind="checked: selected"></label>
        </div>
        <div class="form-group">
            <label class="sr-only" for="key">Key</label>
            <input class="form-control" data-bind="immediate: key" />
        </div>
        <div class="form-group">
            <label class="sr-only" for="value">Value</label>
            <input class="form-control" data-bind="immediate: value" />
        </div>
    </div>
    <!-- /ko -->
</script>
<script type="text/html" id="group-form">
    <div class="form-horizontal">
        <div class="form-group">
            <label class="col-sm-3 control-label">Name</label>
            <div class="col-sm-9">
                <input class="form-control" data-bind="immediate: name" />
            </div>
        </div>
    </div>
</script>
<script type="text/html" id="role-form">
    <div class="form-horizontal">
        <div class="form-group">
            <label class="col-sm-3 control-label">Name</label>
            <div class="col-sm-9">
                <input class="form-control" data-bind="immediate: name" />
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
                <input class="form-control" data-bind="immediate: area" />
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-3 control-label">Permission</label>
            <div class="col-sm-9">
                <input class="form-control" data-bind="immediate: permission" />
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-3 control-label">Description</label>
            <div class="col-sm-9">
                <input class="form-control" data-bind="immediate: description" />
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-3 control-label">Actions</label>
            <div class="col-sm-9">
                <input class="form-control" data-bind="immediate: entered_actions" />
            </div>
        </div>
    </div>
</script>