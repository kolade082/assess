<main class="sidebar">



    <section class="right">
            <h2>Apply for <?= $job['title']; ?></h2>

            <form action="apply" method="POST" enctype="multipart/form-data">
                <label>Full name</label>
                <input type="text" name="name" />

                <label>E-mail address</label>
                <input type="text" name="email" />

                <label>Cover letter</label>
                <textarea name="details"></textarea>

                <label>CV</label>
                <input type="file" name="cv" />

                <input type="hidden" name="jobId" value="<?= $job['id']; ?>" />

                <input type="submit" name="submit" value="Apply" />

            </form>

    </section>
</main>