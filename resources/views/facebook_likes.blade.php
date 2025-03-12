<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Facebook Likes</title>
</head>
<body>
    <h2>Pages Likées</h2>
    <ul id="likes-list"></ul>

    <script>
        // Requête pour récupérer les données depuis l'API
        fetch('/facebook/data')
            .then(response => response.json())
            .then(data => {
                // Vérifier si la donnée "likes" existe
                if (data.likes && Array.isArray(data.likes.data)) {
                    let likesList = document.getElementById('likes-list');
                    data.likes.data.forEach(like => {
                        let li = document.createElement('li');
                        li.textContent = like.name;  // Assurez-vous que "name" est la clé correcte dans votre réponse
                        likesList.appendChild(li);
                    });
                } else {
                    console.error('Aucune donnée de likes trouvée.');
                }
            })
            .catch(error => console.error('Erreur:', error));
    </script>
</body>
  

</html>






