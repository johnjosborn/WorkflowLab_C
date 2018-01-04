<?php

//TODO build list of workflows

$output = " <div class='contentTitle'>
                Add New Item
            </div>
            <div class='contentHolder'>
                <table class='inputTable'>
                    <tr>
                        <th>
                            Item Number
                        </th>
                        <td>
                            <input type='text' id='item_num' class='textTableInput'>
                        </td>
                    </tr>
                    <tr>
                        <th>
                            Item Description
                        </th>
                        <td>
                            <input type='text' id='item_desc' class='textTableInput'>
                        </td>
                    </tr>
                    <tr>
                        <th>
                            Item Status
                        </th>
                        <td>
                            <select id='item_sta' class='textTableInput'>
                                <option value='Active' selected>Active</option>
                                <option value='Inactive'>Inactive</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <th>
                            Default Workflow
                        </th>
                        <td>
                            <select id='item_wf' class='textTableInput'>
                                <option value='0' selected>None</option>
                                <option value=''>1</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                    <th>
                       
                    </th>
                    <td>
                        <button class='button buttonRed' onclick='saveNewItem()'>Save</button>
                        <button class='button buttonGray' onclick='clearNewItem()'>Clear</button>
                    </td>
                </tr>
                </table>
            </div>";

echo $output;

?>