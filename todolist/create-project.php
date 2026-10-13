<dialog id="create-project-dialog">
    <form action="foryou.php" method="post">
        <h2>Create a project</h2>
        <label for="project-name">Project Name</label>
        <input
            id="project-name"
            name="project_name"
            type="text"
            maxlength="100"
            required
        >
        <button type="button" id="create-project-cancel">Cancel</button>
        <button type="submit">Create</button>
    </form>
</dialog>