        $users = DB::table('itens_pedidos')
            ->join('numero_pedido', 'numero_pedido.id', '=', 'numero_pedido.user_id')
            ->join('orders', 'users.id', '=', 'orders.user_id')
            ->select('users.*', 'contacts.phone', 'orders.price')
            ->get();




   C.ID_PEDIDO AS 'PEDIDO',
    --A.ID_PRODUTO,
    A.NOM_PRODUTO AS'PRODUTO',
    A.VAL_PRODUTO AS 'VALOR UNITARIO',
    B.QUANTIDADE,
    (A.VAL_PRODUTO * B.QUANTIDADE)AS 'VALOR TOTAL POR ITEM'
    --B.ID_PRODUTO,

    FROM TB_PRODUTOS A INNER JOIN ITEMS B ON
        A.ID_PRODUTO = B.ID_PRODUTO
        INNER JOIN TB_PEDIDOS C ON C.ID_PEDIDO = B.ID_PEDIDO
        WHERE C.ID_PEDIDO=3
            GROUP BY C.ID_PEDIDO,
            A.NOM_PRODUTO,
            A.VAL_PRODUTO,
            B.VAL_TOTAL ,
            B.QUANTIDADE


SELECT C.id AS 'Pedidos', A.prod_desc AS'PRODUTO', A.prod_preco_padrao AS 'VALOR UNITARIO', B.quantidade, (A.prod_preco_padrao * B.quantidade)AS 'VALOR TOTAL POR ITEM' FROM products A INNER JOIN itens_pedidos B ON A.prod_cod = B.prod_cod INNER JOIN pedidos C ON C.id = B.id WHERE C.id=3 GROUP BY C.id, A.prod_desc, A.prod_preco_padrao, B.quantidade


SELECT C.id AS 'Pedidos',
A.prod_desc AS'PRODUTO', 
A.prod_preco_padrao AS 'VALOR UNITARIO',
 B.quantidade,
  (A.prod_preco_padrao * B.quantidade)AS 'VALOR TOTAL POR ITEM'

   FROM products A INNER JOIN itens_pedidos B ON 
   A.prod_cod = B.prod_cod
    INNER JOIN pedidos C ON C.id = B.id 
    WHERE C.id=3 
    GROUP BY C.id, 
    A.prod_desc, 
    A.prod_preco_padrao, 
    B.quantidade



SELECT C.id AS 'Pedidos',
A.prod_desc AS'PRODUTO', 
A.prod_preco_padrao AS 'VALOR UNITARIO',
B.quantidade,
(A.prod_preco_padrao * B.quantidade)AS 'VALOR TOTAL POR ITEM'

FROM products A INNER JOIN itens_pedidos B ON 
A.prod_cod = B.prod_cod
INNER JOIN pedidos C ON C.id = B.id 
WHERE C.id=3 
GROUP BY C.id, 
A.prod_desc, 
A.prod_preco_padrao, 
B.quantidade



SELECT C.numero_pedido AS 'Pedidos',
A.prod_desc AS'PRODUTO', 
A.prod_preco_padrao AS 'VALOR UNITARIO',
B.quantidade,
(A.prod_preco_padrao * B.quantidade)AS 'VALOR TOTAL POR ITEM'

FROM products A INNER JOIN itens_pedidos B ON 
A.prod_cod = B.prod_cod
INNER JOIN pedidos C ON C.numero_pedido = B.numero_pedido 
WHERE C.numero_pedido=151 
GROUP BY C.numero_pedido, 
A.prod_desc, 
A.prod_preco_padrao, 
B.quantidade

SELECT C.numero_pedido AS 'Pedidos',
A.prod_desc AS'PRODUTO', 
A.prod_preco_padrao AS 'VALOR UNITARIO',
B.quantidade,
(A.prod_preco_padrao * B.quantidade)AS 'VALOR TOTAL POR ITEM'

FROM products A INNER JOIN itens_pedidos B ON 
A.prod_cod = B.prod_cod
INNER JOIN pedidos C ON C.numero_pedido = B.numero_pedido 
WHERE C.numero_pedido=151 
GROUP BY C.numero_pedido, 
A.prod_desc, 
A.prod_preco_padrao, 
B.quantidade