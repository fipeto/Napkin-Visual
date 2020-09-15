<?php
/*©agpl*************************************************************************
*                                                                              *
* Napkin Visual – Visualisation platform for the Napkin platform               *
* Copyright (C) 2020  Napkin AS                                                *
*                                                                              *
* This program is free software: you can redistribute it and/or modify         *
* it under the terms of the GNU Affero General Public License as published by  *
* the Free Software Foundation, either version 3 of the License, or            *
* (at your option) any later version.                                          *
*                                                                              *
* This program is distributed in the hope that it will be useful,              *
* but WITHOUT ANY WARRANTY; without even the implied warranty of               *
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the                 *
* GNU Affero General Public License for more details.                          *
*                                                                              *
* You should have received a copy of the GNU Affero General Public License     *
* along with this program.  If not, see <http://www.gnu.org/licenses/>.        *
*                                                                              *
*****************************************************************************©*/

//include "init_db.php";


function createProject($pdo, $name, $description, $ownerId, $aoi) {
  $stmt = $pdo->prepare("INSERT INTO \"Project\" (name, description, data, aoi) VALUES (?, ?, ?, ?) RETURNING projectid");
  $res = $stmt->execute([
    $name,
    $description,
    '{}',
    json_encode($aoi)
  ]);

  if(!$res) throw new Exception("Failed to execute insert on \"Project\"", 1);

  $row = $stmt->fetch();
  $projectId = $row['projectid'];


  $stmt = $pdo->prepare("INSERT INTO \"User_Project\" (userid, projectid, status) VALUES (?, ?, ?)");
  $res = $stmt->execute([$ownerId, $projectId, 'owner']);

  if(!$res) throw new Exception("Failed to execute insert on \"User_Project\"", 1);

  return array(
    "projectId" => $projectId
  );
}
