<?php include 'import.php'; ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.3/css/bulma.min.css">
    <title>La cuisine</title>
    <style>
        table {
            width: 100%;
        }

        [data-closed="true"] td {
            display: none;
        }

        .click {
            cursor: pointer;
        }
    </style>
</head>

<body>
    <section class="section">
        <?php

        $prixtotal = 0;
        $groupes = json_decode(file_get_contents('ikea.json'), true);

        ?>
        <table class="table">
            <thead>
                <tr>
                    <th></th>
                    <th>Nom</th>
                    <th>Description</th>
                    <th>Prix</th>
                    <th>Quantité</th>
                    <th>Total</th>
                </tr>
            </thead>
            <?php
            foreach ($groupes as $groupe) {
                $total = 0;
            ?>
                <tbody>
                    <tr class="click" onclick="this.closest('tbody').dataset.closed = this.closest('tbody').dataset.closed ? false : true">
                        <th></th>
                        <th colspan="100"><?php echo $groupe['nom']; ?> / <?php echo count($groupe['ps']); ?> références, <?php echo $groupe['total']; ?> produits </th>
                    </tr>
                    <?php
                    foreach ($groupe['ps'] as $p) {
                        if (!isset($p['product']['name'])) {
                            print_r($p);
                            exit;
                        }
                        $prix = $p['qte'] * floatval($p['product']['priceNumeral']);
                        $total += $prix;

                    ?>
                        <tr>
                            <td><a href="<?php echo $p['product']['mainImageUrl']; ?>" target="_blank"><img src="<?php echo $p['product']['mainImageUrl']; ?>" style="width:32px;height:32px;object-fit:cover"></a></td>
                            <td>
                                <a href="<?php echo $p['product']['pipUrl']; ?>" target="_blank"><?php echo $p['product']['name']; ?></a>
                                <p><small><?php echo $p['nom']; ?></small></p>
                            </td>
                            <td><?php echo $p['product']['typeName']; ?><br><?php echo $p['product']['itemMeasureReferenceText']; ?></td>
                            <td><?php echo $p['product']['priceNumeral']; ?>€</td>
                            <td><?php echo $p['qte']; ?></td>
                            <td><?php echo $prix; ?>€</td>


                        </tr>
                    <?php } ?>
                </tbody>
                <tr>
                    <th></th>
                    <th colspan="100">Total : <?php echo $total; ?>€</th>
                </tr>
                <tr>
                    <td colspan="100">&nbsp;</td>
                </tr>

            <?php
                $prixtotal += $total;
            } ?>
        </table>
        <strong>Total final : <?php echo $prixtotal;?> €</strong>
    </section>
</body>

</html>