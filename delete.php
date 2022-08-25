<?php
include 'functions.php';
$pdo = pdo_connect_mysql();
$msg = '';
// Verifica se o ID do contato existe
if (isset($_GET['id'])) {
    // Seleciona o registro que será deletado
    $stmt = $pdo->prepare('SELECT * FROM contacts WHERE id = ?');
    $stmt->execute([$_GET['id']]);
    $contact = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$contact) {
      
        exit('Não existe contato com esse ID!');
    }
    // Certifique-se de que o usuário confirme antes da exclusão
    if (isset($_GET['confirm'])) {
        if ($_GET['confirm'] == 'yes') {
            //O usuário clicou no botão "Sim", exclui registro
            $stmt = $pdo->prepare('DELETE FROM contacts WHERE id = ?');
            $stmt->execute([$_GET['id']]);
            $msg = 'Você excluiu o contato!';
        } else {
            // O usuário clicou no botão "Não", redirecione-o de volta para a página read
            header('Location: read.php');
            exit;
        }
    }
} else {
    exit('ID não especificado!');
}
?>

<?=template_header('Delete')?>

<div class="content delete">
	<h2>Deletar o Contato #<?=$contact['id']?></h2>
    <?php if ($msg): ?>
    <p class="alert alert-danger" role="alert"><?=$msg?></p>
    <?php else: ?>
	<p>Tem certeza de que deseja excluir o contato #<?=$contact['id']?>?</p>
    <div class="yesno">
        <a href="delete.php?id=<?=$contact['id']?>&confirm=yes">Sim</a>
        <a href="delete.php?id=<?=$contact['id']?>&confirm=no">Não</a>
    </div>
    <?php endif; ?>
</div>

<?=template_footer()?>