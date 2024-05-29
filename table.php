 //TODO: table stuff
 <?php
 public static function generateCompetitionTable($conn, $id)
 {
 try {
 $stmt = $conn->prepare("SELECT id, name, rating, confidence FROM $id ORDER BY rating DESC");
 $stmt->execute();
 $items = $stmt->fetchAll(PDO::FETCH_ASSOC);

 // Generate the HTML table
 echo "<table>";
     echo "<tr><th>ID</th><th>Name</th><th>Rating</th><th>Confidence</th></tr>";
     foreach ($items as $item) {
     echo "<tr>";
         echo "<td>" . $item['id'] . "</td>";
         echo "<td>" . htmlspecialchars($item['name']) . "</td>";
         echo "<td>" . $item['rating'] . "</td>";
         echo "<td>" . $item['confidence'] . "</td>";
         echo "</tr>";
     }
     echo "</table>";
 } catch (PDOException $e) {
 throw new Exception("SQL Error: " . $e->getMessage());
 } catch (Exception $e) {
 throw new Exception("Error: " . $e->getMessage());
 }
 }
 ?>

