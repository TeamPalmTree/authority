<?php echo View::forge('manager/forms'); ?>
<div id="authority-manager" class="authority-manager">
    <div class="authority-manager-column">
        <div class="authority-manager-toolbar">
            <nav class="navbar navbar-default" data-bind="css: { active: current_type() == 'user_model' }">
                <div class="navbar-header pull-left">
                    <a class="navbar-brand" href="#" data-bind="click: function() { current_type('user_model'); }">Users</a>
                </div>
                <div class="navbar-header pull-right">
                    <!-- ko if: current_type() == 'user_model' -->
                    <button class="btn btn-default navbar-btn" title="Add User" data-bind="click: add"><span class="glyphicon glyphicon-plus"></span></button>
                    <button class="btn btn-default navbar-btn" title="Modify User" data-bind="click: modify"><span class="glyphicon glyphicon-pencil"></span></button>
                    <button class="btn btn-default navbar-btn" title="Delete Users" data-bind="click: remove"><span class="glyphicon glyphicon-remove"></span></button>
                    <!-- /ko -->
                    <button class="btn btn-default navbar-btn" title="Select All Users" data-bind="click: function() { select_all('user_model'); }"><span class="glyphicon glyphicon-check"></span></button>
                </div>
            </nav>
        </div>
        <div class="authority-manager-filter">
            <input type="text" class="form-control" data-bind="value: users_filter, valueUpdate: 'afterkeydown', hasFocus: current_type() == 'user_model'" />
        </div>
        <div class="authority-manager-table">
            <table class="table">
                <tbody data-bind="foreach: filtered_users">
                    <tr data-bind="css: { 'selected': selected }, click: $root.select">
                        <td>
                            <span data-bind="text: username"></span>
                            <span class="pull-right" data-bind="foreach: assigned_permission_actions">
                                <button class="btn btn-xs btn-primary" data-bind="text: $data, click: function() { $root.assign_permission_actions($parent, $data, false); }, clickBubble: false"></button>
                            </span>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <div class="authority-manager-spacer"></div>
    <div class="authority-manager-column">
        <div class="authority-manager-toolbar">
            <nav class="navbar navbar-default" data-bind="css: { active: current_type() == 'group_model' }">
                <div class="navbar-header pull-left">
                    <a class="navbar-brand" href="#" data-bind="click: function() { current_type('group_model'); }">Groups</a>
                </div>
                <div class="navbar-header pull-right">
                    <!-- ko if: current_type() == 'group_model' -->
                    <button class="btn btn-default navbar-btn" title="Add Group" data-bind="click: add"><span class="glyphicon glyphicon-plus"></span></button>
                    <button class="btn btn-default navbar-btn" title="Modify Group" data-bind="click: modify"><span class="glyphicon glyphicon-pencil"></span></button>
                    <button class="btn btn-default navbar-btn" title="Delete Groups" data-bind="click: remove"><span class="glyphicon glyphicon-remove"></span></button>
                    <!-- /ko -->
                    <button class="btn btn-default navbar-btn" title="Select All Groups" data-bind="click: function() { select_all('group_model'); }"><span class="glyphicon glyphicon-check"></span></button>
                </div>
            </nav>
        </div>
        <div class="authority-manager-column-table-filter">
            <input type="text" class="form-control" data-bind="value: groups_filter, valueUpdate: 'afterkeydown', hasFocus: current_type() == 'group_model'" />
        </div>
        <div class="authority-manager-table">
            <table class="table">
                <tbody data-bind="foreach: filtered_groups">
                    <tr data-bind="css: { 'selected': selected }, click: $root.select">
                        <td>
                            <span data-bind="text: name"></span>
                            <span class="pull-right" data-bind="foreach: assigned_permission_actions">
                                <button class="btn btn-xs btn-primary" data-bind="text: $data, click: function() { $root.assign_permission_actions($parent, $data, false); }, clickBubble: false"></button>
                            </span>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <div class="authority-manager-spacer"></div>
    <div class="authority-manager-column">
        <div class="authority-manager-toolbar">
            <nav class="navbar navbar-default" data-bind="css: { active: current_type() == 'role_model' }">
                <div class="navbar-header pull-left">
                    <a class="navbar-brand" href="#" data-bind="click: function() { current_type('role_model'); }">Roles</a>
                </div>
                <div class="navbar-header pull-right">
                    <!-- ko if: current_type() == 'role_model' -->
                    <button class="btn btn-default navbar-btn" title="Add Role" data-bind="click: add"><span class="glyphicon glyphicon-plus"></span></button>
                    <button class="btn btn-default navbar-btn" title="Modify Role" data-bind="click: modify"><span class="glyphicon glyphicon-pencil"></span></button>
                    <button class="btn btn-default navbar-btn" title="Delete Roles" data-bind="click: remove"><span class="glyphicon glyphicon-remove"></span></button>
                    <!-- /ko -->
                    <button class="btn btn-default navbar-btn" title="Select All Roles" data-bind="click: function() { select_all('role_model'); }"><span class="glyphicon glyphicon-check"></span></button>
                </div>
            </nav>
        </div>
        <div class="authority-manager-filter">
            <input type="text" class="form-control" data-bind="value: roles_filter, valueUpdate: 'afterkeydown', hasFocus: current_type() == 'role_model'" />
        </div>
        <div class="authority-manager-table">
            <table class="table">
                <tbody data-bind="foreach: filtered_roles">
                    <tr data-bind="css: { 'selected': selected }, click: $root.select">
                        <td>
                            <span data-bind="text: name"></span>
                            <span class="pull-right">
                                <span class="label" data-bind="visible: filter, text: filter_text, css: filter_css"></span>
                                <span data-bind="foreach: assigned_permission_actions">
                                    <button class="btn btn-xs btn-primary" data-bind="text: $data, click: function() { $root.assign_permission_actions($parent, $data, false); }, clickBubble: false"></button>
                                </span>
                            </span>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <div class="authority-manager-spacer"></div>
    <div class="authority-manager-column">
        <div class="authority-manager-toolbar">
            <nav class="navbar navbar-default" data-bind="css: { active: current_type() == 'permission_model' }">
                <div class="navbar-header pull-left">
                    <a class="navbar-brand" href="#" data-bind="click: function() { current_type('permission_model'); }">Permissions</a>
                </div>
                <div class="navbar-header pull-right">
                    <!-- ko if: current_type() == 'permission_model' -->
                    <button class="btn btn-default navbar-btn" title="Add Permission" data-bind="click: add"><span class="glyphicon glyphicon-plus"></span></button>
                    <button class="btn btn-default navbar-btn" title="Modify Permission" data-bind="click: modify"><span class="glyphicon glyphicon-pencil"></span></button>
                    <button class="btn btn-default navbar-btn" title="Delete Permissions" data-bind="click: remove"><span class="glyphicon glyphicon-remove"></span></button>
                    <!-- /ko -->
                    <button class="btn btn-default navbar-btn" title="Select All Permissions" data-bind="click: function() { select_all('permission_model'); }"><span class="glyphicon glyphicon-check"></span></button>
                </div>
            </nav>
        </div>
        <div class="authority-manager-column-table-filter">
            <input type="text" class="form-control" data-bind="value: permissions_filter, valueUpdate: 'afterkeydown', hasFocus: current_type() == 'permission_model'" />
        </div>
        <div class="authority-manager-table">
            <table class="table">
                <tbody data-bind="foreach: filtered_permissions">
                    <tr data-bind="css: { 'selected': selected }, click: $root.select">
                        <td>
                            <span class="bold" data-bind="text: area"></span> : <span data-bind="text: permission"></span>
                            <span class="pull-right" data-bind="foreach: actions">
                                <!-- ko if: $root.current_type() == 'permission_model' -->
                                <span class="label" data-bind="text: $data.toUpperCase()"></span>
                                <!-- /ko -->
                                <!-- ko if: $root.current_type() != 'permission_model' -->
                                <button class="btn btn-xs"
                                        data-bind="text: $data, css: { 'btn-primary' : $parent.assigned_actions().indexOf($data) > -1 }, click: function() { $root.assign_permission_actions($parent, $data, $parent.assigned_actions().indexOf($data) == -1); }, clickBubble: false"></button>
                                <!-- /ko -->
                            </span>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>