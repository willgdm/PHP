<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['acao'])) {
        if ($_POST['acao'] == 'salvar') {
            $_SESSION['num1'] = $_POST['num1'];
            $_SESSION['num2'] = $_POST['num2'];
            $_SESSION['operacao'] = $_POST['operacao'];
            $mensagem = "Valores salvos!";
        } elseif ($_POST['acao'] == 'recuperar') {
            $_POST['num1'] = $_SESSION['num1'] ?? '';
            $_POST['num2'] = $_SESSION['num2'] ?? '';
            $_POST['operacao'] = $_SESSION['operacao'] ?? '';
            $mensagem = "Valores recuperados!";
        }
    } elseif (isset($_POST['limpar_historico'])) {
        $_SESSION['historico'] = array();
        $mensagem = "Histórico apagado!";
    } else {
        $num1 = $_POST['num1'];
        $num2 = $_POST['num2'];
        $operacao = $_POST['operacao'];

        $resultado = calcular($num1, $num2, $operacao);

        $historico_item = array(
            'num1' => $num1,
            'num2' => $num2,
            'operacao' => $operacao,
            'resultado' => $resultado
        );
        $_SESSION['historico'][] = $historico_item;

        $mensagem = "O resultado é: $resultado";
    }
}

function calcular($num1, $num2, $operacao)
{
    switch ($operacao) {
        case '+':
            return $num1 + $num2;
        case '-':
            return $num1 - $num2;
        case '*':
            return $num1 * $num2;
        case '/':
            if ($num2 != 0) {
                return $num1 / $num2;
            } else {
                return "Divisão por zero";
            }
        case '^':
            return pow($num1, $num2);
        case '!':
            if ($num1 < 0 || $num1 != intval($num1)) {
                return "Fatorial não definido.";
            }
            $resultado = 1;
            for ($i = 1; $i <= $num1; $i++) {
                $resultado *= $i;
            }
            return $resultado;
        default:
            return "Operação inválida";
    }
}

?>

<!DOCTYPE html>
<htm lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Calculadora</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
            margin: 0;
            padding: 0;
        }

        .center {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .container {
            max-width: 400px;
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .mensagem-box {
            margin-bottom: 10px;
        }

        .mensagem {
            background-color: blue;
            color: white;
            padding: 10px;
            margin-bottom: 10px;
            border-radius: 5px;
            text-align: center;
        }

        h2 {
            color: #333;
            text-align: center;
        }

        form {
            margin-bottom: 20px;
        }

        input[type="text"],
        select,
        button {
            width: calc(100% - 20px);
            padding: 10px;
            margin: 5px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
            box-sizing: border-box;
        }

        button[type="submit"],
        button[name="limpar_historico"] {
            background-color: #4CAF50;
            color: white;
            cursor: pointer;
        }

        button[type="submit"]:hover,
        button[name="limpar_historico"]:hover {
            background-color: #45a049;
        }

        ul {
            list-style-type: none;
            padding: 0;
        }

        li {
            margin-bottom: 10px;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            background-color: #f9f9f9;
    }

        li:hover {
            background-color: #f0f0f0;
    }

    </style>
</head>

<body>
    <div class="center">
        <div class="container">
            <h2>Calculadora</h2>
            <form method="post">
                <div class="mensagem-box">
                    <?php if (isset($mensagem)) : ?>
                    <p class="mensagem"><?php echo $mensagem; ?></p>
                    <?php endif; ?>
                </div>
                <input type="text" name="num1" placeholder="Primeiro número"
                    value="<?php echo $_POST['num1'] ?? ''; ?>">
                <select name="operacao">
                    <option value="+"
                        <?php echo ($_POST['operacao'] ?? '') == '+' ? 'selected' : ''; ?>>+</option>
                    <option value="-"
                        <?php echo ($_POST['operacao'] ?? '') == '-' ? 'selected' : ''; ?>>-</option>
                    <option value="*"
                        <?php echo ($_POST['operacao'] ?? '') == '' ? 'selected' : ''; ?>></option>
                    <option value="/"
                        <?php echo ($_POST['operacao'] ?? '') == '/' ? 'selected' : ''; ?>>/</option>
                    <option value="^"
                        <?php echo ($_POST['operacao'] ?? '') == '^' ? 'selected' : ''; ?>>^</option>
                    <option value="!"
                        <?php echo ($_POST['operacao'] ?? '') == '!' ? 'selected' : ''; ?>>!</option>
                </select>
                <input type="text" name="num2" placeholder="Segundo número"
                    value="<?php echo $_POST['num2'] ?? ''; ?>">
                <button type="submit">Calcular</button>
                <button type="submit" name="acao" value="salvar">Salvar</button>
                <button type="submit" name="acao" value="recuperar">Recuperar</button>
                <button type="submit" name="acao"
                    value="<?php echo isset($_SESSION['num1']) ? 'recuperar' : 'salvar'; ?>">M</button>
                <button type="submit" name="limpar_historico">Limpar Histórico</button>
            </form>
            <h3>Histórico</h3>
            <ul>
                <?php foreach ($_SESSION['historico'] as $item) : ?>
                <li><?php echo "{$item['num1']} {$item['operacao']} {$item['num2']} = {$item['resultado']}"; ?>
                </li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>
</body>

</html>