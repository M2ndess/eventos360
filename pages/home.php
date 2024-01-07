<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: /eventos360/pages/login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Meta tags necessárias -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">

    <!-- Linkar CSS File -->
    <style>
        <?php
            $css = file_get_contents($_SERVER['DOCUMENT_ROOT'] . '/eventos360/assets/css/style.css');
            echo $css;
        ?>
    </style>

    <title>Eventos360</title>
</head>

<body class="body-content">
    <section class="header">
        <div class="container">
            <!-- navbar -->
            <?php include '../includes/header_logado.php'; ?>
            <!-- Frase -->

            <div class="middle-container">
                <div class="middle">
                    <h1 class="text-white fw-bold display-3">Controla os teus eventos <span
                            class="theme-text">com um simples toque!</span></h1>
                </div>
                
                <div class="middle-eventos" id="eventos">
                    <h2 class="fw-bold">Eventos</h2>

                    <div class="icon-container">
                        <svg xmlns="http://www.w3.org/2000/svg" width="200" height="200" fill="currentColor" class="bi bi-1-square-fill" viewBox="0 0 16 16">
                            <path d="M2 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2H2Zm7.283 4.002V12H7.971V5.338h-.065L6.072 6.656V5.385l1.899-1.383h1.312Z"/>
                        </svg>
                        <div class="text-container">
                            <h1>Crie</h1>  
                            <p>Registe-se e crie os seus eventos de uma forma fácil.</p> 
                        </div>  
                    </div>
                    
                    <div class="icon-container2">
                        <div class="text-container">
                            <h3>Customize</h3>  
                            <p>Customize cada um dos seus eventos. Pode categorizar, calenderizar, adicionar notas, fotografias e bilhetes!</p> 
                        </div> 
                        <svg xmlns="http://www.w3.org/2000/svg" width="200" height="200" fill="currentColor" class="bi bi-2-square" viewBox="0 0 16 16">
                            <path d="M6.646 6.24v.07H5.375v-.064c0-1.213.879-2.402 2.637-2.402 1.582 0 2.613.949 2.613 2.215 0 1.002-.6 1.667-1.287 2.43l-.096.107-1.974 2.22v.077h3.498V12H5.422v-.832l2.97-3.293c.434-.475.903-1.008.903-1.705 0-.744-.557-1.236-1.313-1.236-.843 0-1.336.615-1.336 1.306Z"/>
                            <path d="M0 2a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V2Zm15 0a1 1 0 0 0-1-1H2a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V2Z"/>
                          </svg>
                    </div>
                    <div class="icon-container">
                        <svg xmlns="http://www.w3.org/2000/svg" width="200" height="200" fill="currentColor" class="bi bi-3-square-fill" viewBox="0 0 16 16">
                            <path d="M2 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2H2Zm5.918 8.414h-.879V7.342h.838c.78 0 1.348-.522 1.342-1.237 0-.709-.563-1.195-1.348-1.195-.79 0-1.312.498-1.348 1.055H5.275c.036-1.137.95-2.115 2.625-2.121 1.594-.012 2.608.885 2.637 2.062.023 1.137-.885 1.776-1.482 1.875v.07c.703.07 1.71.64 1.734 1.917.024 1.459-1.277 2.396-2.93 2.396-1.705 0-2.707-.967-2.754-2.144H6.33c.059.597.68 1.06 1.541 1.066.973.006 1.6-.563 1.588-1.354-.006-.779-.621-1.318-1.541-1.318Z"/>
                          </svg>
                        <div class="text-container">
                            <h1>Organize</h1>  
                            <p>Organize os seus eventos da forma que necessitar, pode adicionar colaboradores, gestores, pode filtrar e também fazer pesquisas.</p> 
                        </div> 
                    </div>

                    <div class="icon-container2">
                        <div class="text-container">
                            <h3>Partilhe</h3>  
                            <p>Partilhe ou publique cada evento com todos os seus amigos e clientes de forma rápida!</p> 
                        </div> 
                        <svg xmlns="http://www.w3.org/2000/svg" width="200" height="200" fill="currentColor" class="bi bi-4-square" viewBox="0 0 16 16">
                            <path d="M7.519 5.057c.22-.352.439-.703.657-1.055h1.933v5.332h1.008v1.107H10.11V12H8.85v-1.559H4.978V9.322c.77-1.427 1.656-2.847 2.542-4.265ZM6.225 9.281v.053H8.85V5.063h-.065c-.867 1.33-1.787 2.806-2.56 4.218Z"/>
                            <path d="M0 2a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V2Zm15 0a1 1 0 0 0-1-1H2a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V2Z"/>
                          </svg>
                    </div>

                    <p class="fw-bold" style="font-size: 4rem;">Fácil e Eficaz</p>
                    <svg xmlns="http://www.w3.org/2000/svg" width="200" height="200" fill="currentColor" class="bi bi-calendar-date" viewBox="0 0 16 16">
                        <path d="M6.445 11.688V6.354h-.633A12.6 12.6 0 0 0 4.5 7.16v.695c.375-.257.969-.62 1.258-.777h.012v4.61h.675zm1.188-1.305c.047.64.594 1.406 1.703 1.406 1.258 0 2-1.066 2-2.871 0-1.934-.781-2.668-1.953-2.668-.926 0-1.797.672-1.797 1.809 0 1.16.824 1.77 1.676 1.77.746 0 1.23-.376 1.383-.79h.027c-.004 1.316-.461 2.164-1.305 2.164-.664 0-1.008-.45-1.05-.82h-.684zm2.953-2.317c0 .696-.559 1.18-1.184 1.18-.601 0-1.144-.383-1.144-1.2 0-.823.582-1.21 1.168-1.21.633 0 1.16.398 1.16 1.23z"/>
                        <path d="M3.5 0a.5.5 0 0 1 .5.5V1h8V.5a.5.5 0 0 1 1 0V1h1a2 2 0 0 1 2 2v11a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V3a2 2 0 0 1 2-2h1V.5a.5.5 0 0 1 .5-.5zM1 4v10a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V4H1z"/>
                      </svg>

                </div>

                <div class="middle-about-us" id="sobre">
                    <h2 class="fw-bold">Sobre Nós</h2>
                    <p>Somos uma equipe dedicada que acredita na simplicidade e eficiência. A nossa missão é oferecer-lhe
                        as ferramentas certas para controlar, customizar e gerir os seus eventos com facilidade. Com um simples toque,
                        você pode transformar a organização dos seus eventos e garantir experiências memoráveis
                        para todos os envolvidos.</p>
                </div>

                <div class="middle-contact" id="contactos">
                    <h2 class="fw-bold">Contacte-nos</h2>
                    <form id="contactForm">
                        <div class="mb-3">
                            <label for="inputEmail" class="form-label">Email</label>
                            <input type="email" class="form-control" id="inputEmail"
                                placeholder="Seu endereço de email" required>
                        </div>
                        <div class="mb-3">
                            <label for="inputSubject" class="form-label">Assunto</label>
                            <input type="text" class="form-control" id="inputSubject"
                                placeholder="Assunto da mensagem" required>
                        </div>
                        <div class="mb-3">
                            <label for="inputMessage" class="form-label">Mensagem</label>
                            <textarea class="form-control" id="inputMessage" rows="5"
                                placeholder="Sua mensagem" required></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Enviar</button>
                    </form>
                </div>
                    <!-- Footer -->
                    <?php include '../includes/footer.php'; ?>
            </div>
    </section>

        <!-- Wave -->
        <svg class="wave" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320">
            <path fill="#fff" fill-opacity="1"
                d="M0,224L80,208C160,192,320,160,480,170.7C640,181,800,235,960,224C1120,213,1280,139,1360,101.3L1440,64L1440,320L1360,320C1280,320,1120,320,960,320C800,320,640,320,480,320C320,320,160,320,80,320L0,320Z"></path>
        </svg>

        <!-- Conteúdo principal acaba aqui -->
        
        <!-- Script para ajustar a posição da wave à medida que a middle-container sobe -->
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                var wave = document.querySelector('.wave');
        
                window.addEventListener('scroll', function () {
                    var scrollPosition = window.scrollY;
        
                    // Ajusta a posição da wave conforme o scroll
                    wave.style.transform = 'translateY(' + scrollPosition / 2 + 'px)';
                });
            });
        </script>

        <script 
            src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL"
            crossorigin="anonymous">
        </script>

        <!-- Script para ajustar o posicionamento do título -->
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                var navbar = document.querySelector('.navbar');
                var middleSection = document.querySelector('.middle');

                navbar.addEventListener('show.bs.collapse', function () {
                    middleSection.style.top = navbar.clientHeight + 110 + 'px';
                });

                navbar.addEventListener('hide.bs.collapse', function () {
                    middleSection.style.top = '0';
                });
            });
        </script>        
</body>
</html>
