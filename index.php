<form action="index.php" method="POST">

    <input type="text" name="ndp"
    placeholder="nome do produto"/><br/>
    <br/>
    <select name="op">
        <option value="s1">setor 1</option>
        <option value="s2">setor 2</option>
        <option value="s3">setor 3</option>
        <option value="s4">setor 4</option>
    </select><br/>
    <br/>
    <input type="text" name="pdc"
    placeholder="preço de custo"/><br/>
    <br/>
    <input type="text" name="pdv"
    placeholder="preço de venda"/><br/>
    <br/>
    <input type="number" name="estoque"
    placeholder="estoque"/><br/>
    <br/>
    <input type="submit" name="grava"
    value="Gravar"/>
    </form>

    <br/>
    <br/>

    <?php
    include "conn.php";
    if(isset($_GET['alterar'])){
    $id=$_GET['id'];
    $consu=$conn->prepare('
    SELECT * FROM `cadastro` 
    WHERE `id_prod`= :pid;');
    $consu->bindValue(':pid',$id);
    
    $consu->execute();
    $row=$consu->fetch();
    
    ?>    
    <form action="index.php" method="POST">
    <input type="hidden" name="id_prod"
    value="<?php echo $row['id_prod']; ?>">
    <input type="text" name="nome_prod"
    placeholder="Nome" value="<?php echo $row['nome_prod'];?>"/><br/>
    <input type="text" name="setor_prod"
    placeholder="setor" value="<?php echo $row['setor_prod'];?>"/><br/>
    <input type="text" name="custo_prod"
    placeholder="preço de custo" value="<?php echo $row['custo_prod'];?>"/><br/>
    <input type="text" name="venda_prod"
    placeholder="preco de venda" value="<?php echo $row['venda_prod'];?>"/><br/>
    <input type="number" name="estoque_prod"
    placeholder="estoque" value="<?php echo $row['estoque_prod'];?>"/><br/>
    <br/>
    <input type="submit" name="altera"
    value="Alterar"/>

</form>

<?php
    }
include "conn.php";

if(isset($_POST['altera'])){
    $id=$_POST['id_prod'];
    $nome=$_POST['nome_prod'];
    $op=$_POST['setor_prod'];
    $preco_de_custo=$_POST['custo_prod'];
    $preco_de_venda=$_POST['venda_prod'];
    $estoque=$_POST['estoque_prod'];
    $situacao_do_produto=1;

    $altera=$conn->prepare("
    UPDATE `cadastro` SET `nome_prod` = :pnome, 
    `setor_prod` = :psetor, 
    `custo_prod` = :pcusto, 
    `venda_prod` = :pvenda, 
    `estoque_prod` = :pestoque, 
    `situacao_prod` = :psituacao WHERE 
    `cadastro`.`id_prod` = :pid;
    ");
    $altera->bindvalue(':pid',$id);
    $altera->bindValue(':pnome',$nome);
    $altera->bindValue(':psetor',$op);
    $altera->bindValue(':pvenda',$preco_de_custo);
    $altera->bindValue(':pcusto',$preco_de_venda);
    $altera->bindValue(':pestoque',$estoque);
    $altera->bindvalue(':psituacao',$situacao_do_produto);
    $altera->execute();
    echo "Cadastro alterado com sucesso!";
}

if(isset($_POST['grava'])){
    $nome=$_POST['ndp'];
    $op=$_POST['op'];
    $preco_de_custo=$_POST['pdc'];
    $preco_de_venda=$_POST['pdv'];
    $estoque=$_POST['estoque'];
    $situacao_do_produto=1;
    $grava=$conn->prepare('INSERT INTO
     `cadastro` (`id_prod`, `nome_prod`, 
     `setor_prod`, `custo_prod`, `venda_prod`, `estoque_prod`, `situacao_prod`) VALUES 
     (NULL, :pnome, :psetor, :pcusto, :pvenda, :pestoque, :psituacao);');
    $grava->bindValue(':pnome',$nome);
    $grava->bindValue(':psetor',$op);
    $grava->bindValue(':pvenda',$preco_de_custo);
    $grava->bindValue(':pcusto',$preco_de_venda);
    $grava->bindValue(':pestoque',$estoque);
    $grava->bindValue(':psituacao',$situacao_do_produto);
    $grava->execute();
    echo "Gravado!";
}

if(isset($_GET['excluir'])){
    $id=$_GET['id'];
    $excluir=$conn->prepare('DELETE 
    FROM cadastro WHERE 
    `cadastro`.`id_prod` = :pid');
    $excluir->bindValue(':pid',$id);
    $excluir->execute();
    echo "Excluído com sucesso!";
}

?>
<table border="1">
    <tr>
        <th>Nome do produto</th>
        <th>Setor do produto</th>
        <th>Preço de custo</th>
        <th>Preço de venda</th>
        <th>Estoque</th>
        <th></th>
    </tr>
    <?php
    $exibir=$conn->prepare('
    SELECT * FROM `cadastro`');
    $exibir->execute();
    if($exibir->rowCount()==0){
        echo "Não há registros";
    }else{
        while($row=$exibir->fetch()){
            echo "<tr>";
            echo "<td>".$row['nome_prod']."</td>";
            echo "<td>".$row['setor_prod']."</td>";
            echo "<td>".$row['custo_prod']."</td>";
            echo "<td>".$row['venda_prod']."</td>";
            echo "<td>".$row['estoque_prod']."</td>";
            echo "<td><a href='index.php?excluir&id=".$row['id_prod']."'>Excluir</a></td>";
            echo "<td><a href='index.php?alterar&id=".$row['id_prod']."'>Alterar</a></td>";
            echo "</tr>";
        }
    }
    ?>
</table>