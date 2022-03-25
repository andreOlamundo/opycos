
         CadastroWhatsapp::where([
            'preview_id'  => $preview_id
           
            ])->update([
                'status' => 'A'
                'name'
                'cel'
                'email'
                'msg'
            ]);

                     Cliente::where([
            'id'  => $preview_id

           
            ])->update([
                'status' => 'A'
                'name'
                'cel'
                'email'
                
            ]);
