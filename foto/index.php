<!DOCTYPE html>
<html lang="hu">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link
            href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css"
            rel="stylesheet"
            integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3"
            crossorigin="anonymous">
        <title>Képbeszúrás, Mysql, PHP</title>
    </head>
    <body>
        <?php
        $uzenet="";
        if ($_SERVER['REQUEST_METHOD']=='POST')
        {
            $img = $_FILES['kep']['tmp_name'];
            $kep = file_get_contents($img);

            $con = mysqli_connect('127.0.0.1','root','root','kepek') or die('nem sikerült csatlakozni!');
            
            $sql="insert into kepek (kep) value(?)";
            $stmt = mysqli_prepare($con, $sql);
            mysqli_stmt_bind_param($stmt, "s", $kep);
            mysqli_stmt_execute($stmt);

            $ellenorzes = mysqli_stmt_affected_rows($stmt);
            if($ellenorzes==1) {
                $uzenet = 'A képfeltöltés sikerült!';
            }else {
                $uzenet = 'Hiba a feltöltés során!';
            }

            mysqli_close($con);
        }
        ?>
        <div class="container-fluid">
            <form action="" method="post">

                <div class="mb-3">
                    <label for="kep" class="form-label">Kép</label>
                    <input type="file" class="form-control" id="kep">
                </div>

                <button type="submit" class="btn btn-primary">Feltöltés</button>
            </form>
            <div class="row">
                <div class="col display-3">
                <?php
                    echo $uzenet;
                ?>
                </div>
            </div>
            <div class="row">
                <?php
                    $con = mysqli_connect('127.0.0.1','root','root','kepek') or die('Nem sikerült csatlakozni az adatbázishoz!');
                    $sql = "select * from kepek;";
                    $eredmeny = mysqli_query($con, $sql);

                    while($sor = mysqli_fetch_array($eredmeny))
                    {
                        echo '<div class="col">';
                            echo '<img class="image-fluid" alt="kép" src ="data:image/jpeg;base64, '.base64_encode($sor['kep']).'">';
                            echo '<a href="delete.php?id='.$sor['id'].'">Törlés</a>';
                        echo '</div>';
                    }

                    mysqli_close($con);
                ?>
            </div>

        </div>
    </body>
</html>
