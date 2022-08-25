<?php
include 'functions.php';

// Conecta ao banco de dados MySQL
$pdo = pdo_connect_mysql();

// Obtém a página via solicitação GET (parâmetro de URL: page), se não existir, padroniza a página para 1
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;

// Número de registros a serem exibidos em cada página
$records_per_page = 5;

// Prepara a instrução SQL e obtém registros da nossa tabela de contatos, LIMIT determinará a página
$stmt = $pdo->prepare('SELECT * FROM contacts ORDER BY id LIMIT :current_page, :record_per_page');
$stmt->bindValue(':current_page', ($page-1)*$records_per_page, PDO::PARAM_INT);
$stmt->bindValue(':record_per_page', $records_per_page, PDO::PARAM_INT);
$stmt->execute();

// Busca os registros para exibi-los em no template.
$contacts = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Obtém o número total de contatos, para que possamos determinar se deve haver um botão próximo e anterior
$num_contacts = $pdo->query('SELECT COUNT(*) FROM contacts')->fetchColumn();
?>

<?=template_header('Read')?>

<div class="content read">
	<h2>Contatos</h2>
	<a href="create.php" class="create-contact">Criar Contato</a>
	<table>
    <thead>
        <tr>
            <td>#</td>
            <td>Nome</td>
            <td>E-mail</td>
            <td>Telefone</td>
            <td>Titulo</td>
            <td>Criando em</td>
            <td></td>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($contacts as $contact): ?>
        <tr>
            <td><?=$contact['id']?></td>
            <td><?=$contact['name']?></td>
            <td><?=$contact['email']?></td>
            <td><?=$contact['phone']?></td>
            <td><?=$contact['title']?></td>
            <td><?=$contact['created']?></td>
            <td class="actions">
                <a href="update.php?id=<?=$contact['id']?>" class="edit"><i class="fas fa-pen fa-xs"></i></a>
                <a href="delete.php?id=<?=$contact['id']?>" class="trash"><i class="fas fa-trash fa-xs"></i></a>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
  </table>

	<div class="pagination">
		<?php if ($page > 1): ?>
		<a href="read.php?page=<?=$page-1?>"><i class="fas fa-angle-double-left fa-sm"></i></a>
		<?php endif; ?>
		<?php if ($page*$records_per_page < $num_contacts): ?>
		<a href="read.php?page=<?=$page+1?>"><i class="fas fa-angle-double-right fa-sm"></i></a>
		<?php endif; ?>
	</div>
</div>

<?=template_footer()?>