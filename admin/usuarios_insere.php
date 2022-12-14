<?php
//Sistema de autenticação
include('acesso_com.php');
//Variaveis de ambiente
include('../config.php');
//Conexão com banco
include('../conexoes/conexao.php');


if($_POST){
    
        if (isset($_POST['enviar'])) {
            $nome_img = $_FILES['foto_usuario']['name'];
            $tmp_img = $_FILES['foto_usuario']['tmp_name'];
            $pasta_img = "../images/" . $nome_img;
            move_uploaded_file($tmp_img, $pasta_img);
        }
    
        
        $id_nivel_usuario = $_POST['id_nivel_usuario'];
        $login_usuario = $_POST['login_usuario'];        
        $senha_usuario = $_POST['senha_usuario'];       
        $foto_usuario = $_FILES['foto_usuario']['name'];

        $campos_insert = "id_nivel_usuario,login_usuario,senha_usuario,foto_usuario";
        $values = "$id_nivel_usuario,'$login_usuario','$senha_usuario','$foto_usuario'";
        
        $query = "insert into tbusuarios ($campos_insert) values ($values);";
        $resultado = $conexao->query($query);

     // var_dump($$query);

    //Após o insert direciona a pagina
   if(mysqli_insert_id($conexao)){
        header("location: usuarios_lista.php");
    }else{
        header("location: usuarios_lista.php");
    } 
}


//Chave estrangeira tipo
$query_tipo = "select * from tbnivel order by nome_nivel asc";
$lista_fk = $conexao->query($query_tipo);
$linha_fk = $lista_fk->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        <<?php echo SYS_NAME; ?> - Inserir Usuários </title>
            <link rel="stylesheet" href="../css/bootstrap.min.css" type="text/css">
            <link rel="stylesheet" href="../css/meu_estilo.css" type="text/css">
</head>

<body class="fundofixo">
    <?php include('menu_adm.php'); ?>
    <main class="container">
        <div class="row">
            <div class="col-xs-12 col-sm-offset-3 col-sm-6 col-md-offset-2 col-md-8">
                <h2 class="breadcrumb tex-danger">
                    <a href="usuarios_lista.php">
                        <button class="btn btn-danger">
                            <span class="glyphicon glyphicon-chevron-left"></span>
                        </button>
                    </a>
                    Inserindo Usuários
                </h2>
                <div class="thumbnail">
                    <!-- Abre thumbnail -->
                    <div class="alert alert-danger" role="alert">
                        <form action="usuarios_insere.php" method="post" id="form_usuarios_insere" name="form_usuarios_insere" enctype="multipart/form-data">
                            <!--Inserir o campo id_produto oculto para uso no filtro -->
                            <input type="hidden" name="id_usuario" id="id_usuario">
                            <!-- Select id_tipo_produto -->
                            <label for="id_tipo_produto">Nível:</label>
                            <div class="input-group">
                                <span class="input-group-addon">
                                    <span class="glyphicon glyphicon-briefcase" aria-hidden="true"></span>
                                </span>

                                <select name="id_nivel_usuario" id="id_nivel_usuario" class="form-control" required>
                                    <?php do { ?>
                                        <option value="<?php echo $linha_fk['id_nivel']; ?>">
                                            <?php echo $linha_fk['nome_nivel']; ?>
                                        </option>
                                    <?php } while ($linha_fk = $lista_fk->fetch_assoc());
                                    $linha_fk = mysqli_num_rows($lista_fk);
                                    if ($linha_fk > 0) {
                                        mysqli_data_seek($lista_fk, 0);
                                        $linha_fk = $lista_fk->fetch_assoc();
                                    }
                                    ?>
                                </select>
                            </div>
                            <br>
                            <!-- Text descri_produto -->
                            <label for="login_usuario">Login:</label>
                            <div class="input-group">
                                <span class="input-group-addon">
                                    <span class="glyphicon glyphicon-user" aria-hidden="true"></span>
                                </span>
                                <input type="text" class="form-control" id="login_usuario" name="login_usuario" maxlength="100" required  placeholder="Digite o login do novo usuário">
                            </div>
                            <br>
                            <!-- number valor_produto -->
                            <label for="senha_usuario">Senha:</label>
                            <div class="input-group">
                                <span class="input-group-addon">
                                    <span class="glyphicon glyphicon-lock" aria-hidden="true"></span>
                                </span>
                                <input type="password" name="senha_usuario" id="senha_usuario" required class="form-control">
                            </div>
                            <br>
                            <!-- file imagem-->
                            <label for="foto_usuario">Foto:</label>
                            <div class="input-group">
                                <span class="input-group-addon">
                                    <span class="glyphicon glyphicon-picture" aria-hidden="true"></span>
                                </span>
                                <img src="" alt="" name="foto" id="foto" class="img-responsive">
                                <input type="file" name="foto_usuario" id="foto_usuario" class="form-control" accept="image/*">
                            </div>
                            <br>
                            <!-- Botão Enviar -->
                            <input type="submit" value="Cadastrar" name="enviar" id="enviar" class="btn btn-success btn-block">
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <!-- Script para a imagem -->
    <script>
        document.getElementById("foto_usuario").onchange = function() {
            var reader = new FileReader();
            if (this.files[0].size > 528385) {
                alert("A imagem deve ter no máximo 500KB");
                $("#foto").attr("src", "blank");
                $("#foto").hide();
                $("#foto_usuario").wrap('<form>').closest('form').get(0).reset();
                $("#ifoto_usuario").unwrap();
                return false;

            }
            // Verifica se o input do titpo file possui dado
            if (this.files[0].type.indexOf("image") == -1) {
                alert("Formato inválido, escolha uma imagem!");
                $("#foto").attr("src", "blank");
                $("#foto").hide();
                $("#foto_usuario").wrap('<form>').closest('form').get(0).reset();
                $("#foto_usuario").unwrap();
                return false;
            };
            reader.onload = function(e) {
                //Obter dados  carregados e renderizar a miniatura
                document.getElementById("foto").src = e.target.result;
                $("#foto").show();
            };
            reader.readAsDataURL(this.files[0]);
        };
    </script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="../js/bootstrap.min.js"></script>
</body>

</html>