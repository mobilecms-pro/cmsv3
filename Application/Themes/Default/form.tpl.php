<form 
    class="form-horizontal"
    action="<?php echo $form['url'] ?>" 
    method="<?php echo $form['method'] ?>" 
    <?php echo ($form['upload'] === true ? 'enctype="multipart/form-data"' : '')?>
>
    <?php foreach ($form['elements'] as $element): ?>
    <?php if (in_array($element['type'], ['text', 'email', 'password', 'file'])): ?>
    <div class="form-group">
        <input class="form-control" 
            name="<?php echo $element['name'] ?>"
            type="<?php echo $element['type'] ?>" 
            id="help<?php echo $element['id'] ?>" 
            aria-describedby="help<?php echo $element['id'] ?>" 
            placeholder="<?php echo $element['placeholder'] ?>"
            value="<?php echo $element['value'] ?>"
            <?php echo ($element['disabled'] === true ? 'disable' : '')?>
        />
        <?php if ( ! empty($element['help'])): ?>
        <small 
            id="emailHelp<?php echo $element['id'] ?>" 
            class="form-text text-muted"
        ><?php echo $element['help'] ?></small>
        <?php endif;?>
    </div>
    <?php endif; ?> 
    <?php if ($element['type'] === 'textarea'):?>
    <div class="form-group">
        <textarea class="form-control"
            name="<?php echo $element['name'] ?>"
            id="help<?php echo $element['id'] ?>" 
            rows="3"
            placeholder="<?php echo $element['placeholder'] ?>"
        ><?php echo $element['value'] ?></textarea>
        <?php if ( ! empty($element['help'])): ?>
        <small 
            id="emailHelp<?php echo $element['id'] ?>" 
            class="form-text text-muted"
        ><?php echo $element['help'] ?></small>
        <?php endif;?>
    </div>
    <?php endif; ?>
    <?php if ($element['type'] === 'checkbox'):?>
    <div class="form-check">
        <input
            name="<?php echo $element['name'] ?>" 
            type="checkbox" 
            class="checkbox" 
            id="help<?php echo $element['id'] ?>"
        >
      <label class="form-check-label" for="help<?php echo $element['id'] ?>">
          <?php echo $element['placeholder'] ?>
      </label>
    </div>
    <?php endif; ?>
    <?php if ($element['type'] === 'submit'):?>
    <button
        name="<?php echo $element['name'] ?>" 
        type="submit" 
        class="btn btn-primary form-control"
    ><?php echo $element['placeholder'] ?></button>
    <?php endif; ?>
    <?php if ($element['type'] === 'captcha'):?>
    <div class="form-group">
    <script type="text/javascript" src="https://www.google.com/recaptcha/api.js?hl=ru"> </script>
    <center>
    <div class="g-recaptcha" data-sitekey="<?php echo $googleCaptcha ?>"></div>
    </center>
    </div>
    <?php endif; ?>
    <?php if ($element['type'] === 'select'):?>
    <div class="form-group">
        <select
            name="<?php echo $element['name'] ?>"  
            class="form-control"
            id="help<?php echo $element['id'] ?>"
        >
            <?php foreach ($element['options'] as $select): ?>
            <option 
                value="<?php echo $select['value'] ?>"
                <?php echo ($select['selected'] === true ? 'selected="selected"' : '')?>
            >
                <?php echo $select['name'] ?>
            </option>
            <?php endforeach; ?>
        </select>
    </div>
    <?php endif; ?>
    <?php endforeach;?>
</form>