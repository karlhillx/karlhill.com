import imagemin from 'imagemin';
import imageminWebp from 'imagemin-webp';

(async () => {
    const files = await imagemin(['public/img/*.{jpg,png}'], {
        destination: 'public/img/webp',
        plugins: [
            imageminWebp({
                quality: 75,
                method: 6
            })
        ]
    });

    console.log('Images optimized:', files.length);
})();
