<?php include('src/app/Views/includes/head.php'); ?>
<?php include('src/app/Views/includes/header.php'); ?>

<h2>Gestion des événements</h2>

<form method="POST" action="/admin_evenements" enctype="multipart/form-data">
    <input type="hidden" name="id" id="event_id">

    <label for="title">Titre :</label>
    <input type="text" name="title" id="title" required>

    <label for="description">Description :</label>
    <textarea name="description" id="description"></textarea>

    <label for="date">Date :</label>
    <input type="date" name="date" id="date" required>

    <label for="time">Heure :</label>
    <input type="time" name="time" id="time" required>

    <label for="location">Lieu :</label>
    <input type="text" name="location" id="location">

    <label for="image">Image :</label>
    <input type="file" name="image" id="image" accept="image/*">
    <input type="hidden" name="existing_image" id="existing_image">

    <button type="submit" name="add">Ajouter</button>
    <button type="submit" name="update">Modifier</button>
</form>

<h3>Événements existants</h3>
<table>
    <tr>
        <th>Image</th><th>Titre</th><th>Date</th><th>Heure</th><th>Lieu</th><th>Actions</th>
    </tr>
    <?php foreach ($events as $event) : ?>
        <tr>
            <td><img src="assets/images/events/<?= htmlspecialchars($event['image'] ?? 'assets/images/events/placeholder.jpg') ?>" width="50"></td>
            <td><?= htmlspecialchars($event['title']) ?></td>
            <td><?= htmlspecialchars($event['date_event']) ?></td>
            <td><?= htmlspecialchars($event['time_event']) ?></td>
            <td><?= htmlspecialchars($event['location']) ?></td>
            <td>
                <button onclick="editEvent(<?= $event['id'] ?>, '<?= $event['title'] ?>', '<?= $event['description'] ?>', '<?= $event['date_event'] ?>', '<?= $event['time_event'] ?>', '<?= $event['location'] ?>', '<?= $event['image'] ?>')">Modifier</button>
                <form method="POST" action="admin_evenements" style="display:inline;">
                    <input type="hidden" name="id" value="<?= $event['id'] ?>">
                    <button type="submit" name="delete">Supprimer</button>
                </form>
            </td>
        </tr>
    <?php endforeach; ?>
</table>

<script>
function editEvent(id, title, description, date, time, location, image) {
    document.getElementById('event_id').value = id;
    document.getElementById('title').value = title;
    document.getElementById('description').value = description;
    document.getElementById('date').value = date;
    document.getElementById('time').value = time;
    document.getElementById('location').value = location;
    document.getElementById('existing_image').value = image;
}
</script>

<?php include('src/app/Views/includes/footer.php'); ?>