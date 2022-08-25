<?php
include 'functions.php';
$pdo = pdo_connect_mysql();
$msg = '';

// Verifique se o id do contato existe, por exemplo update.php?id=1 obterá o contato com o id 1
if (isset($_GET['id'])) {
    if (!empty($_POST)) {
        // Esta parte é semelhante ao create.php, mas em vez disso atualizamos um registro e não inserimos
        $id = isset($_POST['id']) ? $_POST['id'] : NULL;
        $name = isset($_POST['name']) ? $_POST['name'] : '';
        $email = isset($_POST['email']) ? $_POST['email'] : '';
        $phone = isset($_POST['phone']) ? $_POST['phone'] : '';
        $title = isset($_POST['title']) ? $_POST['title'] : '';
        $created = isset($_POST['created']) ? $_POST['created'] : date('Y-m-d H:i:s');
        // Atualiza o registro
        $stmt = $pdo->prepare('UPDATE contacts SET id = ?, name = ?, email = ?, phone = ?, title = ?, created = ? WHERE id = ?');
        $stmt->execute([$id, $name, $email, $phone, $title, $created, $_GET['id']]);
        $msg = 'Atualizado com sucesso!';
    }
    // Busca o contato na tabela de contacts
    $stmt = $pdo->prepare('SELECT * FROM contacts WHERE id = ?');
    $stmt->execute([$_GET['id']]);
    $contact = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$contact) {
        exit('Não existe contato com esse ID!');
    }
} else {
    exit('ID não especificado!');
}
?>
<?=template_header('Read')?>
<?php if ($msg): ?>
    <p class="alert alert-info" role="alert"><?=$msg?></p>
    <?php endif; ?>
<div class="content update">
	<h2>Atualizar Contato #<?=$contact['id']?></h2>
    <form action="update.php?id=<?=$contact['id']?>" method="post">
        <label for="id">ID</label>
        <label for="name">Nome</label>
        <input type="text" name="id" placeholder="1" value="<?=$contact['id']?>" id="id">
        <input type="text" name="name" placeholder="John Doe" value="<?=$contact['name']?>" id="name">
        <label for="email">E-mail</label>
        <label for="phone">Telefone</label>
        <input type="text" name="email" placeholder="johndoe@example.com" value="<?=$contact['email']?>" id="email">
        <input type="text" name="phone" placeholder="2025550143" value="<?=$contact['phone']?>" id="phone">
        <label for="title">Titulo</label>
        <label for="created">Criado em</label>
        <input type="text" name="title" placeholder="Employee" value="<?=$contact['title']?>" id="title">
        <input type="datetime-local" name="created" value="<?=date('Y-m-d\TH:i', strtotime($contact['created']))?>" id="created">
        <input type="submit" value="Atualizar Contato">
    </form>
    
</div>
<?=template_footer()?>
