<?php 
	class view_robot {
		public function view_dinamic_manager(array $victro_fields, array $victro_results, array $victro_actions = array()) {
        GLOBAL $victro_robot;
        GLOBAL $victro_pg;
        GLOBAL $victro_searchlink;
        GLOBAL $victro_totalpg;
        $victro_string_manager = '';
        $victro_string_manager .= '<form method="GET">';
        $victro_string_manager .= '	<div class="input-group">';
        $victro_string_manager .= '		<div class="input-group-btn">';
        $victro_string_manager .= '			<a href="#modal-new" data-toggle="modal" class="btn btn-primary" >' . bot_translate('Add', 1, true) . '</a>';
        $victro_string_manager .= '		</div>';
        $victro_string_manager .= '		<input type="text" name="s" class="form-control" placeholder="' . bot_translate('search here', 1, true) . '">';
        $victro_string_manager .= '		<div class="input-group-btn">';
        $victro_string_manager .= '			<button type="submit" class="btn btn-success">' . bot_translate('find', 1, true) . '</button>';
        $victro_string_manager .= '		</div>';
        $victro_string_manager .= '	</div>';
        $victro_string_manager .= '</form>';
        $victro_string_manager .= '<BR>';
        $victro_string_manager .= '<table id="data-table" class="table table-striped table-bordered">';
        $victro_string_manager .= '<thead>';
        $victro_string_manager .= '<tr>';
        $victro_string_manager .= '<th>' . bot_translate('ID', 2, true) . '</th>';
        foreach ($victro_fields as $victro_labels) {
            $victro_string_manager .= '<th>' . bot_translate($victro_labels['label'], 1, true) . '</th>';
        }
        $victro_string_manager .= '<th>' . bot_translate('actions', 1, true) . '</th>';
        $victro_string_manager .= '</tr>';
        $victro_string_manager .= '</thead>';
        $victro_string_manager .= '<tbody>';
        if ($victro_results['count'] > 0) {
            foreach ($victro_results['array'] as $victro_result) {
                $victro_string_manager .= '<tr class="odd gradeA">';
                $victro_string_manager .= '<td>' . $victro_result['id'] . '</td>';
                foreach ($victro_fields as $victro_field) {
                    if (isset($victro_field['option'])) {
                        $victro_find_option = false;
                        foreach ($victro_field['option'] as $victro_option) {
                            if ($victro_result[$victro_field['name']] == $victro_option['value']) {
                                $victro_string_manager .= '<td>' . $victro_option['label'] . '</td>';
                                $victro_find_option = true;
                            }
                        }
                        if ($victro_find_option == false) {
                            $victro_string_manager .= '<td>?</td>';
                        }
                    } else if (isset($victro_field['table'])) {
                        $this->select($victro_field['table']['field']);
                        $this->from($victro_field['table']['table']);
                        $this->where($victro_field['table']['where'] . ' = ' . $victro_result[$victro_field['table']['where_field']]);
                        $victro_query = $this->db_select();
                        if ($victro_query['count'] > 0) {
                            $victro_string_manager .= '<td>' . $victro_query['array'][0][$victro_field['table']['field']] . '</td>';
                        } else {
                            goto elsemanager;
                        }
                    } else {
                        elsemanager:
                        $victro_string_manager .= '<td>' . $victro_result[$victro_field['name']] . '</td>';
                    }
                }
                $victro_string_manager .= '<td>';
                foreach ($victro_actions as $victro_action) {
                    $victro_action = str_replace('(ID)', $victro_result['id'], $victro_action);
                    $victro_string_manager .= $victro_action;
                }
                $victro_string_manager .= '</td>';
                $victro_string_manager .= '</tr>';
            }
        } else {
            $victro_string_manager .= '<tr class="odd gradeA"><td colspan="' . (count($victro_fields) + 2) . '">' . bot_translate('nothing found', 1, true) . '</td></tr>';
        }
        $victro_string_manager .= '</tbody>';
        $victro_string_manager .= '</table>';
        $victro_string_manager .= '<div align="center">';
        $victro_string_manager .= '	<ul class="pagination pagination-lg m-t-0 m-b-10">';
        if ($victro_pg == 1) {
            $victro_string_manager .= '<li class="disabled"><a href="javascript:;">«</a></li>';
        } else {
            $victro_string_manager .= '<li><a href="' . $victro_robot['full_w_link'] . $victro_robot['action'] . '/?pg=' . ($victro_pg - 1) . $victro_searchlink . '">«</a></li>';
        }
        for ($victro_pgs = 1; $victro_pgs <= $victro_totalpg; $victro_pgs++) {
            if ($victro_pgs == $victro_pg) {
                $victro_active = "active";
            } else {
                $victro_active = "";
            }
            $victro_string_manager .= '<li class="' . $victro_active . '"><a href="' . $victro_robot['full_w_link'] . $victro_robot['action'] . '/?pg=' . ($victro_pgs) . $victro_searchlink . '">' . $victro_pgs . '</a></li>';
        }
        if ($victro_pg >= $victro_totalpg or $victro_totalpg == 0) {
            $victro_string_manager .= '<li class="disabled"><a href="javascript:;">»</a></li>';
        } else {
            $victro_string_manager .= '<li><a href="' . $victro_robot['full_w_link'] . $victro_robot['action'] . '/?pg=' . ($victro_pg + 1) . $victro_searchlink . '">»</a></li>';
        }
        $victro_string_manager .= '</ul>';
        $victro_string_manager .= '</div>';
        return $victro_string_manager;
    }

    public function view_dinamic_delete($victro_field, $victro_register, $victro_results) {
        $victro_string_delete = '';
        if ($victro_results['count'] > 0) {
            foreach ($victro_results['array'] as $victro_result) {
                $victro_string_delete .= '<div class="modal modal-message fade" id="modal-delete' . $victro_result['id'] . '">';
                $victro_string_delete .= '	<div class="modal-dialog">';
                $victro_string_delete .= '		<div class="modal-content">';
                $victro_string_delete .= '			<div class="modal-header">';
                $victro_string_delete .= '				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>';
                $victro_string_delete .= '				<h4 class="modal-title">' . bot_translate('Delete ' . $victro_register, 1, true) . '</h4>';
                $victro_string_delete .= '			</div>';
                $victro_string_delete .= '			<div class="modal-body panel-form">';
                $victro_string_delete .= '				<form class="form-horizontal" method="POST">';
                $victro_string_delete .= '					<input type="hidden" name="iddel" value="' . $victro_result['id'] . '">';
                $victro_string_delete .= '					Are you sure about delete ' . $victro_result[$victro_field] . '?<BR>';
                $victro_string_delete .= '					<BR><button type="submit" name="cadastrar1" class="btn btn-sm btn-primary m-r-5">' . bot_translate('YES', 1, true) . '</button>';
                $victro_string_delete .= '					<button type="button" class="btn btn-sm btn-primary m-r-5" data-dismiss="modal" aria-hidden="true">' . bot_translate('Cancel', 1, true) . '</button>';
                $victro_string_delete .= '				</form>';
                $victro_string_delete .= '			</div>';
                $victro_string_delete .= '		</div>';
                $victro_string_delete .= '	</div>';
                $victro_string_delete .= '</div>';
                $victro_array_delete[] = $victro_string_delete;
                $victro_string_delete = '';
            }
            return(implode('', $victro_array_delete));
        }
    }

    public function view_dinamic_fields(array $victro_fields, $victro_return = 'text', array $victro_extra = array(), array $victro_results = array(), $victro_type = 'html', $victro_translate = true) {
        $victro_array_fields = array();
        if ($victro_type == 'html') {
            if (count($victro_results) == 0) {
                $victro_string_fields = '';
                if (isset($victro_extra['before'])) {
                    $victro_string_fields .= $victro_extra['before'];
                }
                $victro_cont = 0;
                foreach ($victro_fields as $victro_field) {
                    $victro_type_input = (isset($victro_field['type']) ? $victro_field['type'] : 'text');
                    $victro_string_fields .= '<div class="form-group" ' . ($victro_type_input == 'hidden' ? 'style="display:none"' : '') . '>';
                    $victro_string_fields .= '		<label for="' . $victro_field['name'] . '">' . bot_translate($victro_field['label'], 1, true) . ':</label>';
                    if (!isset($victro_field['option'])) {
                        $victro_string_fields .= '		<input type="' . $victro_type_input . '" ' . (isset($victro_field['minlength']) ? 'data-parsley-minlength="' . $victro_field['minlength'] . '"' : '') . ' ' . (isset($victro_field['required']) and $victro_field['required'] == true ? 'data-parsley-required="true"' : '') . ' value="' . (isset($victro_field['value']) ? $victro_field['value'] : '') . '" class="form-control ' . (isset($victro_field['class']) ? $victro_field['class'] : '') . '" name="' . $victro_field['name'] . '" id="' . $victro_field['name'] . ' ' . (isset($victro_field['onblur']) ? 'onblur="' . $victro_field['onblur'] . '"' : '') . '">';
                    } else {
                        $victro_string_fields .= '		<select ' . (isset($victro_field['required']) and $victro_field['required'] == true ? 'data-parsley-required="true"' : '') . ' class="form-control ' . (isset($victro_field['class']) ? $victro_field['class'] : '') . '" name="' . $victro_field['name'] . '" id="' . $victro_field['name'] . ' ' . (isset($victro_field['onchange']) ? 'onchange="' . $victro_field['onchange'] . '"' : '') . '">';
                        foreach ($victro_field['option'] as $victro_option) {
                            $victro_string_fields .= '<option value="' . $victro_option['value'] . '">' . $victro_option['label'] . '</option>';
                        }
                        $victro_string_fields .= '</select>';
                    }
                    $victro_string_fields .= '</div>';
                    $victro_array_fields[] = $victro_string_fields;
                    $victro_string_fields = '';
                    $victro_cont++;
                }
                if (isset($victro_extra['after'])) {
                    $victro_array_fields[] = $victro_extra['after'];
                }
            } else {
                $victro_cont = 0;
                foreach ($victro_results['array'] as $victro_result) {
                    $victro_string_fields = '';
                    if (isset($victro_extra['before'])) {
                        $victro_before = str_replace('(ID)', $victro_result['id'], $victro_extra['before']);
                        $victro_string_fields .= $victro_before;
                    }
                    $victro_string_fields .= '<input type="hidden" value="' . $victro_result['id'] . '" name="idedit">';
                    foreach ($victro_fields as $victro_field) {
                        $victro_type_input = (isset($victro_field['type']) ? $victro_field['type'] : 'text');
                        $victro_string_fields .= '<div class="form-group" ' . ($victro_type_input == 'hidden' ? 'style="display:none"' : '') . '>';
                        $victro_string_fields .= '		<label for="lang">' . bot_translate($victro_field['label'], 1, true) . ':</label>';
                        if (!isset($victro_field['option'])) {
                            $victro_string_fields .= '		<input type="' . $victro_type_input . '" ' . (isset($victro_field['minlength']) ? 'data-parsley-minlength="' . $victro_field['minlength'] . '"' : '') . ' ' . (isset($victro_field['required']) and $victro_field['required'] == true ? 'data-parsley-required="true"' : '') . ' value="' . $victro_result[$victro_field['name']] . '" class="form-control ' . (isset($victro_field['class']) ? $victro_field['class'] : '') . '" name="' . $victro_field['name'] . '" id="' . $victro_field['name'] . ' ' . (isset($victro_field['onblur']) ? 'onblur="' . $victro_field['onblur'] . '"' : '') . '">';
                        } else {
                            $victro_string_fields .= '		<select ' . (isset($victro_field['required']) and $victro_field['required'] == true ? 'data-parsley-required="true"' : '') . ' class="form-control ' . (isset($victro_field['class']) ? $victro_field['class'] : '') . '" name="' . $victro_field['name'] . '" id="' . $victro_field['name'] . ' ' . (isset($victro_field['onchange']) ? 'onchange="' . $victro_field['onchange'] . '"' : '') . '">';
                            foreach ($victro_field['option'] as $victro_option) {
                                $victro_string_fields .= '<option ' . ($victro_result[$victro_field['name']] == $victro_option['value'] ? 'selected' : '' ) . ' value="' . $victro_option['value'] . '">' . $victro_option['label'] . '</option>';
                            }
                            $victro_string_fields .= '</select>';
                        }
                        $victro_string_fields .= '</div>';
                        $victro_array_fields[] = $victro_string_fields;
                        $victro_string_fields = '';
                        $victro_cont++;
                    }
                    if (isset($victro_extra['after'])) {
                        $victro_array_fields[] = $victro_extra['after'];
                    }
                }
            }
            if ($victro_return == 'text') {
                return(implode('', $victro_array_fields));
            } else {
                return($victro_array_fields);
            }
        }
    }

    public function controller_var_fields($victro_fields = array(), $victro_extraspost = array(), $victro_extras = array()) {
        foreach ($victro_fields as $victro_field) {
            if (is_array($victro_field)) {
                $victro_databasefield[$victro_field['name']] = $_POST[$victro_field['name']];
            } else {
                $victro_databasefield[$victro_field] = $_POST[$victro_field];
            }
        }
        foreach ($victro_extras as $victro_key => $victro_extra) {
            $victro_databasefield[$victro_key] = $victro_extra;
        }
        foreach ($victro_extraspost as $victro_key => $victro_extrapost) {
            if (is_array($victro_extrapost)) {
                $victro_databasefield[$victro_extrapost['name']] = $_POST[$victro_extrapost['name']];
            } else {
                $victro_databasefield[$victro_key] = $_POST[$victro_extrapost];
            }
        }
        return($victro_databasefield);
    }
	}
?>