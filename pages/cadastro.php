<div class="container">
    <?php include('../includes/sidebar.php'); ?>

    <div class="container-cadastro">
        <h2>Cadastro</h2>
        <p style="text-align: center;">Clique em uma das opções abaixo para cadastrar:</p>

        <div class="nav-buttons">
            <button class="nav-button" data-form="form-funcionario">Funcionario</button>
            <button class="nav-button" data-form="form-motorista">Motorista</button>
            <button class="nav-button" data-form="form-transportadora">Transportadora</button>
            <button class="nav-button" data-form="form-usuario_sistema">Usuario do Sistema</button>
            <button class="nav-button" data-form="form-visitante">Visitante</button>
        </div>

        <!-- Cadastro Funcionario -->
        <form id="form-funcionario" class="formulario-cadastro" method="POST" action="cadastro.php" style="display:none;">
            <h3>Cadastro Funcionario</h3>
            <input type="hidden" name="tipoCadastro" value="funcionario">
            <div class="input-group">
                <div style="flex: 1; margin-right: 10px;">
                    <label for="nome">Nome:</label>
                    <input type="text" name="nome" required>
                </div>
                <div style="flex: 1;">
                    <label for="telefone">Telefone:</label>
                    <input type="text" name="telefone" required>
                </div>
            </div>
            <button type="submit">Cadastrar Funcionario</button>
        </form>

        <!-- Cadastro Motorista -->
        <form id="form-motorista" class="formulario-cadastro" method="POST" action="cadastro.php" style="display:none;">
            <h3>Cadastro Motorista</h3>
            <input type="hidden" name="tipoCadastro" value="motorista">
            <div class="input-group">
                <div style="flex: 1; margin-right: 10px;">
                    <label for="nome">Nome:</label>
                    <input type="text" name="nome" required>
                </div>
                <div style="flex: 1;">
                    <label for="doc_identidade">Documento de Identidade:</label>
                    <input type="text" name="doc_identidade" required>
                </div>
            </div>
            <div class="input-group">
                <div style="flex: 1; margin-right: 10px;">
                    <label for="telefone">Telefone:</label>
                    <input type="text" name="telefone" required>
                </div>
                <div style="flex: 1;">
                    <label for="transportadora_id">Transportadora (ID):</label>
                    <input type="text" name="transportadora_id" required>
                </div>
            </div>
            <button type="submit">Cadastrar Motorista</button>
        </form>

        <!-- Cadastro Transportadora -->
        <form id="form-transportadora" class="formulario-cadastro" method="POST" action="cadastro.php" style="display:none;">
            <h3>Cadastro Transportadora</h3>
            <input type="hidden" name="tipoCadastro" value="transportadora">
            <div class="input-group">
                <div style="flex: 1; margin-right: 10px;">
                    <label for="nome">Nome:</label>
                    <input type="text" name="nome" required>
                </div>
                <div style="flex: 1;">
                    <label for="telefone">Telefone:</label>
                    <input type="text" name="telefone" required>
                </div>
            </div>
            <div class="input-group">
                <div style="flex: 1; margin-right: 10px;">
                    <label for="endereco">Endereco:</label>
                    <input type="text" name="endereco" required>
                </div>
            </div>
            <button type="submit">Cadastrar Transportadora</button>
        </form>

        <!-- Cadastro Usuario de Sistema -->
        <form id="form-usuario_sistema" class="formulario-cadastro" method="POST" action="cadastro.php" style="display:none;">
            <h3>Cadastro Usuario de Sistema</h3>
            <input type="hidden" name="tipoCadastro" value="usuario_sistema">
            <div class="input-group">
                <div style="flex: 1; margin-right: 10px;">
                    <label for="nome">Nome:</label>
                    <input type="text" name="nome" required>
                </div>
                <div style="flex: 1;">
                    <label for="senha">Senha:</label>
                    <input type="password" name="senha" required>
                </div>
            </div>
            <button type="submit">Cadastrar Usuario de Sistema</button>
        </form>

        <!-- Cadastro Visitante -->
        <form id="form-visitante" class="formulario-cadastro" method="POST" action="cadastro.php" style="display:none;">
            <h3>Cadastro Visitante</h3>
            <input type="hidden" name="tipoCadastro" value="visitante">
            <div class="input-group">
                <div style="flex: 1; margin-right: 10px;">
                    <label for="nome">Nome:</label>
                    <input type="text" name="nome" required>
                </div>
                <div style="flex: 1;">
                    <label for="doc_identidade">Documento de Identidade:</label>
                    <input type="text" name="doc_identidade" required>
                </div>
            </div>
            <div class="input-group">
                <div style="flex: 1; margin-right: 10px;">
                    <label for="empresa">Empresa:</label>
                    <input type="text" name="empresa" required>
                </div>
                <div style="flex: 1;">
                    <label for="veiculo">Veículo:</label>
                    <input type="text" name="veiculo">
                </div>
            </div>
            <div class="input-group">
                <div style="flex: 1; margin-right: 10px;">
                    <label for="telefone">Telefone:</label>
                    <input type="text" name="telefone">
                </div>
                <div style="flex: 1;">
                    <label for="finalidade_id">Finalidade (ID):</label>
                    <input type="text" name="finalidade_id" required>
                </div>
            </div>
            <button type="submit">Cadastrar Visitante</button>
        </form>

    </div>
</div>

<script>
    // Lógica para alternar entre os formulários
    document.querySelectorAll('.nav-button').forEach(button => {
        button.addEventListener('click', function () {
            const formId = this.getAttribute('data-form');
            document.querySelectorAll('.formulario-cadastro').forEach(form => {
                form.style.display = (form.id === formId) ? 'block' : 'none';
            });
        });
    });
</script>
